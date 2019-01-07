<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application\Routing;

use LogicException;
use Silex\Application;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Shared\Kernel\Store;

/**
 * @deprecated Will be removed without replacement.
 *
 * @see Router Module.
 */
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
            [$controllerNamespaceName, $actionName] = explode('::', $destination);
        } elseif (strpos($destination, ':') !== false) {
            [$serviceName, $actionName] = explode(':', $destination);
            $controllerNamespaceName = get_class($this->app[$serviceName]);
        } else {
            throw new LogicException('Cannot parse destination');
        }
        [$namespace, $application, $bundle, $layer, $controllerName] = explode('\\', $controllerNamespaceName);

        $bundle = str_replace(Store::getInstance()->getStoreName(), '', $bundle);

        $utilTextService = new UtilTextService();
        $controller = $utilTextService->camelCaseToSeparator(str_replace('Controller', '', $controllerName));
        $action = $utilTextService->camelCaseToSeparator((str_replace('Action', '', $actionName)));

        return $bundle . '/' . $controller . '/' . $action;
    }
}
