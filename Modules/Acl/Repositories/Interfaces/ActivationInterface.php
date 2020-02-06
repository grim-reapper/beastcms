<?php

namespace Modules\Acl\Repositories\Interfaces;

use Modules\Acl\Entities\User;

interface ActivationInterface
{
    /**
     * Create a new activation record and code.
     *
     * @param  \Modules\Acl\Models\User $user
     * @return \Modules\Acl\Models\Activation
     */
    public function createUser(User $user);

    /**
     * Checks if a valid activation for the given user exists.
     *
     * @param  \Modules\Acl\Models\User $user
     * @param  string $code
     * @return \Modules\Acl\Models\Activation|bool
     */
    public function exists(User $user, $code = null);

    /**
     * Completes the activation for the given user.
     *
     * @param  \Modules\Acl\Models\User $user
     * @param  string $code
     * @return bool
     */
    public function complete(User $user, $code);

    /**
     * Checks if a valid activation has been completed.
     *
     * @param  \Modules\Acl\Models\User $user
     * @return \Modules\Acl\Models\Activation|bool
     */
    public function completed(User $user);

    /**
     * Remove an existing activation (deactivate).
     *
     * @param  \Modules\Acl\Models\User $user
     * @return bool|null
     */
    public function remove(User $user);

    /**
     * Remove expired activation codes.
     *
     * @return int
     */
    public function removeExpired();
}
