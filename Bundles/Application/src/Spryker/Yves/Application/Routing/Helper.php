<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application\Routing;

use Silex\Application;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Library\Filter\CamelCaseToSeparatorFilter;

class Helper
{

    /**
     * @var \Silex\Application
     */
    protected $app;

    /**
     * @param \Silex\Application $app
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
        list(, , $bundle, , $controllerName) = explode('\\', $controllerNamespaceName);

        $filter = new CamelCaseToSeparatorFilter('-');

        $bundle = str_replace(Store::getInstance()->getStoreName(), '', $bundle);
        $controller = $filter->filter(str_replace('Controller', '', $controllerName));
        $action = $filter->filter(str_replace('Action', '', $actionName));

        return $bundle . '/' . $controller . '/' . $action;
    }

}
