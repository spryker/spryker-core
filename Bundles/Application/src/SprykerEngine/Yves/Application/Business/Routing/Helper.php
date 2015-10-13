<?php

namespace SprykerEngine\Yves\Application\Business\Routing;

use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Shared\Library\Filter\CamelCaseToSeparatorFilter;
use Silex\Application;

class Helper
{

    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $destination
     *
     * @throws \LogicException
     *
     * @return string
     */
    public function getRouteFromDestination($destination)
    {
        if (strpos($destination, '::') !== false) {
            list($controllerNamespaceName, $actionName) = explode('::', $destination);
        } elseif (strpos($destination, ':') !== false) {
            list($serviceName, $actionName) = explode(':', $destination);
            $controllerNamespaceName = get_class($this->app[$serviceName]);
        } else {
            throw new \LogicException('Cannot parse destination');
        }
        list(, , $bundle, , , $controllerName) = explode('\\', $controllerNamespaceName);

        $filter = new CamelCaseToSeparatorFilter('-');

        $bundle = str_replace(Store::getInstance()->getStoreName(), '', $bundle);
        $bundle = $filter->filter($bundle);
        $controller = $filter->filter(str_replace('Controller', '', $controllerName));
        $action = $filter->filter(str_replace('Action', '', $actionName));

        return $bundle . '/' . $controller . '/' . $action;
    }

}
