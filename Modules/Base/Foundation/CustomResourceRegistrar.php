<?php


namespace Modules\Base\Foundation;
use Illuminate\Routing\ResourceRegistrar;

class CustomResourceRegistrar extends ResourceRegistrar
{
    protected $resourceDefaults = ['index', 'create', 'store', 'edit', 'update', 'destroy'];

    protected function getResourceRouteName($resource, $method, $options)
    {
        switch ($method) {
            case 'store':
                $method = 'create';
                break;
            case 'update':
                $method = 'edit';
                break;
        }
        return parent::getResourceRouteName($resource, $method, $options);
    }

    protected function addResourceEdit($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/' . static::$verbs['edit'] . '/{' . $base . '}';

        $action = $this->getResourceAction($name, $controller, 'edit', $options);

        return $this->router->get($uri, $action);
    }

    protected function addResourceUpdate($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/' . static::$verbs['edit'] . '/{' . $base . '}';

        $action = $this->getResourceAction($name, $controller, 'update', $options);

        return $this->router->post($uri, $action);
    }

    protected function addResourceStore($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/' . static::$verbs['create'];

        $action = $this->getResourceAction($name, $controller, 'store', $options);

        return $this->router->post($uri, $action);
    }
}
