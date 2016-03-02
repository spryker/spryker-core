<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Business\Model\Twig;

class RouteResolver
{

    /**
     * @param string $controllerServiceName
     *
     * @return string
     */
    public function buildRouteFromControllerServiceName($controllerServiceName)
    {
        list($serviceName, $actionName) = explode(':', $controllerServiceName);
        $serviceNameParts = explode('.', $serviceName);

        return $serviceNameParts[2] . '/' . $serviceNameParts[3] . '/' . $serviceNameParts[4];
    }

}
