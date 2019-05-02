<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business\Model;

use Zend\Filter\Word\CamelCaseToDash;

/**
 * @deprecated Use \Spryker\Zed\Twig\Communication\RouteResolver\RouteResolver instead.
 */
class RouteResolver
{
    /**
     * @param string $controllerServiceName
     *
     * @return string
     */
    public function buildRouteFromControllerServiceName($controllerServiceName)
    {
        [$serviceName, $actionName] = explode(':', $controllerServiceName);
        $serviceNameParts = explode('.', $serviceName);

        $serviceNameParts[4] = $this->getCamelCaseToDash($serviceNameParts[4]);

        return $serviceNameParts[2] . '/' . $serviceNameParts[3] . '/' . $serviceNameParts[4];
    }

    /**
     * @param string $incomingString
     *
     * @return string
     */
    protected function getCamelCaseToDash($incomingString)
    {
        $filter = new CamelCaseToDash();

        return strtolower($filter->filter($incomingString));
    }
}
