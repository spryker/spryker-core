<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\Twig;

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
