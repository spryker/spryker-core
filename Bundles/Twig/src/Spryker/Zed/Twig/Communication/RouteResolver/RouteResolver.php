<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication\RouteResolver;

use Zend\Filter\Word\CamelCaseToDash;

class RouteResolver implements RouteResolverInterface
{
    protected const SERVICE_NAME_INDEX_MODULE = 2;
    protected const SERVICE_NAME_INDEX_CONTROLLER = 3;
    protected const SERVICE_NAME_INDEX_TEMPLATE_NAME = 4;

    /**
     * @param string $controllerServiceName
     *
     * @return string
     */
    public function buildRouteFromControllerServiceName(string $controllerServiceName): string
    {
        [$serviceName, $actionName] = explode(':', $controllerServiceName);
        $serviceNameParts = explode('.', $serviceName);

        $serviceNameParts[static::SERVICE_NAME_INDEX_TEMPLATE_NAME] = $this->getCamelCaseToDash($serviceNameParts[static::SERVICE_NAME_INDEX_TEMPLATE_NAME]);

        return $serviceNameParts[static::SERVICE_NAME_INDEX_MODULE]
            . '/' . $serviceNameParts[static::SERVICE_NAME_INDEX_CONTROLLER]
            . '/' . $serviceNameParts[static::SERVICE_NAME_INDEX_TEMPLATE_NAME];
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
