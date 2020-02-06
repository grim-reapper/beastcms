<?php

namespace Modules\Acl\Http\Controllers;

use Assets;
use Illuminate\Support\Facades\Auth;
use Modules\Acl\Forms\PasswordForm;
use Modules\Acl\Forms\ProfileForm;
use Modules\Acl\Forms\UserForm;
use Modules\Acl\Tables\UserTable;
use Modules\Acl\Http\Requests\CreateUserRequest;
use Modules\Acl\Http\Requests\UpdatePasswordRequest;
use Modules\Acl\Http\Requests\UpdateProfileRequest;
use Modules\Acl\Models\UserMeta;
use Modules\Acl\Repositories\Interfaces\RoleInterface;
use Modules\Acl\Repositories\Interfaces\UserInterface;
use Modules\Acl\Services\ChangePasswordService;
use Modules\Acl\Services\CreateUserService;
use Modules\Base\Events\CreatedContentEvent;
use Modules\Base\Events\DeletedContentEvent;
use Modules\Base\Forms\FormBuilder;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Base\Http\Responses\BaseHttpResponse;
use Modules\Media\Repositories\Interfaces\MediaFileInterface;
use Modules\Media\Services\ThumbnailService;
use Modules\Media\Services\UploadsManager;
use Modules\Acl\Http\Requests\AvatarRequest;
use Exception;
use File;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use RvMedia;
use Storage;

class UserController extends BaseController
{

    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * @var RoleInterface
     */
    protected $roleRepository;

    /**
     * @var MediaFileInterface
     */
    protected $fileRepository;

    /**
     * UserController constructor.
     * @param UserInterface $userRepository
     * @param RoleInterface $roleRepository
     * @param MediaFileInterface $fileRepository
     */
    public function __construct(
        UserInterface $userRepository,
        RoleInterface $roleRepository,
        MediaFileInterface $fileRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * Display all users
     * @param UserTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Throwable
     */
    public function index(UserTable $dataTable)
    {
        page_title()->setTitle(trans('Acl::users.users'));

        Assets::addScripts(['bootstrap-editable'])
            ->addStyles(['bootstrap-editable']);

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(__('Create new user'));

        return $formBuilder->create(UserForm::class)->renderForm();
    }

    /**
     * @param CreateUserRequest $request
     * @param CreateUserService $service
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(CreateUserRequest $request, CreateUserService $service, BaseHttpResponse $response)
    {
        $user = $service->execute($request);

        event(new CreatedContentEvent(USER_MODULE_SCREEN_NAME, $request, $user));

        return $response
            ->setPreviousUrl(route('users.index'))
            ->setNextUrl(route('user.profile.view', $user->id))
            ->setMessage(trans('Base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy($id, Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->getKey() == $id) {
            return $response
                ->setError()
                ->setMessage(trans('Acl::users.delete_user_logged_in'));
        }

        try {
            $user = $this->userRepository->findOrFail($id);

            if (!$request->user()->isSuperUser() && $user->isSuperUser()) {
                return $response
                    ->setError()
                    ->setMessage(__('Permission denied. Cannot delete a super user!'));
            }

            $this->userRepository->delete($user);
            event(new DeletedContentEvent(USER_MODULE_SCREEN_NAME, $request, $user));

            return $response->setMessage(trans('Acl::users.deleted'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(trans('Acl::users.cannot_delete'));
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('Base::notices.no_select'));
        }

        foreach ($ids as $id) {
            if ($request->user()->getKey() == $id) {
                return $response
                    ->setError()
                    ->setMessage(trans('Acl::users.delete_user_logged_in'));
            }
            try {
                $user = $this->userRepository->findOrFail($id);
                if (!$request->user()->isSuperUser() && $user->isSuperUser()) {
                    continue;
                }
                $this->userRepository->delete($user);
                event(new DeletedContentEvent(USER_MODULE_SCREEN_NAME, $request, $user));
            } catch (Exception $ex) {
                return $response
                    ->setError()
                    ->setMessage(trans('Acl::users.cannot_delete'));
            }
        }

        return $response->setMessage(trans('Acl::users.deleted'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View| \Illuminate\Http\RedirectResponse
     */
    public function getUserProfile($id, Request $request, FormBuilder $formBuilder)
    {

        Assets::addScripts(['bootstrap-pwstrength', 'cropper'])
            ->addScriptsDirectly('vendor/core/js/profile.js');

        $user = $this->userRepository->findOrFail($id);

        page_title()->setTitle(__(':name', ['name' => $user->getFullName()]));

        $form = $formBuilder
            ->create(ProfileForm::class, ['model' => $user])
            ->setUrl(route('users.update-profile', $user->id));
        $passwordForm = $formBuilder
            ->create(PasswordForm::class)
            ->setUrl(route('users.change-password', $user->id));

        $canChangeProfile = $request->user()->getKey() == $id || $request->user()->isSuperUser();

        if (!$canChangeProfile) {
            $form->disableFields();
            $form->removeActionButtons();
            $passwordForm->disableFields();
            $passwordForm->removeActionButtons();
        }

        if ($request->user()->isSuperUser()) {
            $passwordForm->remove('old_password');
        }
        $form = $form->renderForm();
        $passwordForm = $passwordForm->renderForm();

        return view('Acl::users.profile.base', compact('user', 'form', 'passwordForm', 'canChangeProfile'));
    }

    /**
     * @param int $id
     * @param UpdateProfileRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postUpdateProfile($id, UpdateProfileRequest $request, BaseHttpResponse $response)
    {
        $user = $this->userRepository->findOrFail($id);

        $currentUser = $request->user();
        if (($currentUser->hasPermission('users.update-profile') && $currentUser->getKey() === $user->id) ||
            $currentUser->isSuperUser()
        ) {
            if ($user->email !== $request->input('email')) {
                $users = $this->userRepository->getModel()
                    ->where('email', $request->input('email'))
                    ->where('id', '<>', $user->id)
                    ->count();
                if ($users) {
                    return $response
                        ->setError()
                        ->setMessage(trans('Acl::users.email_exist'))
                        ->withInput();
                }
            }

            if ($user->username !== $request->input('username')) {
                $users = $this->userRepository->getModel()
                    ->where('username', $request->input('username'))
                    ->where('id', '<>', $user->id)
                    ->count();
                if ($users) {
                    return $response
                        ->setError()
                        ->setMessage(trans('Acl::users.username_exist'))
                        ->withInput();
                }
            }
        }

        $user->fill($request->input());
        $this->userRepository->createOrUpdate($user);
        do_action(USER_ACTION_AFTER_UPDATE_PROFILE, USER_MODULE_SCREEN_NAME, $request, $user);

        return $response->setMessage(trans('Acl::users.update_profile_success'));
    }

    /**
     * @param int $id
     * @param UpdatePasswordRequest $request
     * @param ChangePasswordService $service
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postChangePassword(
        $id,
        UpdatePasswordRequest $request,
        ChangePasswordService $service,
        BaseHttpResponse $response
    ) {
        $request->merge(['id' => $id]);
        $result = $service->execute($request);

        if ($result instanceof Exception) {
            return $response
                ->setError()
                ->setMessage($result->getMessage());
        }

        return $response->setMessage(trans('Acl::users.password_update_success'));
    }

    /**
     * @param AvatarRequest $request
     * @param UploadsManager $uploadManager
     * @param ImageManager $imageManager
     * @param ThumbnailService $thumbnailService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postAvatar(
        $id,
        AvatarRequest $request,
        UploadsManager $uploadManager,
        ImageManager $imageManager,
        ThumbnailService $thumbnailService,
        BaseHttpResponse $response
    ) {
        try {
            $fileUpload = $request->file('avatar_file');

            $fileExt = $fileUpload->getClientOriginalExtension();

            $folderPath = 'users';

            $fileName = $this->fileRepository->createName(File::name($fileUpload->getClientOriginalName()), 0);

            $fileName = $this->fileRepository->createSlug($fileName, $fileExt, Storage::path($folderPath));

            $userId = $request->user()->getKey();

            if ($request->user()->isSuperUser()) {
                $userId = $id;
            }

            $user = $this->userRepository->findOrFail($userId);

            $image = $imageManager->make($request->file('avatar_file')->getRealPath());
            $avatarData = json_decode($request->input('avatar_data'));
            $image->crop((int)$avatarData->height, (int)$avatarData->width, (int)$avatarData->x, (int)$avatarData->y);
            $path = $folderPath . '/' . $fileName;

            $uploadManager->saveFile($path, $image->stream()->__toString());

            $readableSize = explode('x', RvMedia::getSize('thumb'));

            $thumbnailService
                ->setImage($fileUpload->getRealPath())
                ->setSize($readableSize[0], $readableSize[1])
                ->setDestinationPath($folderPath)
                ->setFileName(File::name($fileName) . '-' . RvMedia::getSize('thumb') . '.' . $fileExt)
                ->save();

            $data = $uploadManager->fileDetails($path);

            $file = $this->fileRepository->getModel();
            $file->name = $fileName;
            $file->url = $data['url'];
            $file->size = $data['size'];
            $file->mime_type = $data['mime_type'];
            $file->folder_id = 0;
            $file->user_id = 0;
            $file->options = [];
            $file = $this->fileRepository->createOrUpdate($file);

            $this->fileRepository->forceDelete(['id' => $user->avatar_id]);

            $user->avatar_id = $file->id;

            $this->userRepository->createOrUpdate($user);

            return $response
                ->setMessage(trans('Acl::users.update_avatar_success'))
                ->setData(['url' => Storage::url($data['url'])]);
        } catch (Exception $ex) {
            return $response
                ->setError()
                ->setMessage($ex->getMessage());
        }
    }

    /**
     * @param string $lang
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function getLanguage($lang, Request $request)
    {
        if ($lang != false && array_key_exists($lang, Assets::getAdminLocales())) {
            if (Auth::check()) {
                UserMeta::setMeta('admin-locale', $lang);
                cache()->forget(md5('cache-dashboard-menu-' . $request->user()->getKey()));
            }
            session()->put('admin-locale', $lang);
        }

        return redirect()->back();
    }

    /**
     * @param string $theme
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getTheme($theme)
    {
        if (Auth::check()) {
            UserMeta::setMeta('admin-theme', $theme);
        }

        session()->put('admin-theme', $theme);

        try {
            return redirect()->back();
        } catch (Exception $exception) {
            return redirect()->route('access.login');
        }
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function makeSuper($id, BaseHttpResponse $response)
    {
        try {
            $user = $this->userRepository->findOrFail($id);

            $user->updatePermission(ACL_ROLE_SUPER_USER, true);
            $user->updatePermission(ACL_ROLE_MANAGE_SUPERS, true);
            $user->super_user = 1;
            $user->manage_supers = 1;
            $this->userRepository->createOrUpdate($user);

            return $response
                ->setNextUrl(route('users.index'))
                ->setMessage(trans('Base::system.supper_granted'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setNextUrl(route('users.index'))
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function removeSuper($id, Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->getKey() == $id) {
            return $response
                ->setError()
                ->setMessage(trans('Base::system.cannot_revoke_yourself'));
        }

        $user = $this->userRepository->findOrFail($id);

        $user->updatePermission(ACL_ROLE_SUPER_USER, false);
        $user->updatePermission(ACL_ROLE_MANAGE_SUPERS, false);
        $user->super_user = 0;
        $user->manage_supers = 0;
        $this->userRepository->createOrUpdate($user);

        return $response
            ->setNextUrl(route('users.index'))
            ->setMessage(trans('Base::system.supper_revoked'));
    }
}
