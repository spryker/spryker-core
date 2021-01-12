<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication\Controller;

use Laminas\Filter\Word\DashToCamelCase;
use Spryker\Shared\Kernel\Communication\RouteNameResolverInterface;
use Symfony\Component\HttpFoundation\Request;

class RouteNameResolver implements RouteNameResolverInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function resolve()
    {
        $bundle = $this->dashToCamelCase($this->request->attributes->get('module'));
        $controller = $this->dashToCamelCase($this->request->attributes->get('controller'));
        $action = lcfirst($this->dashToCamelCase($this->request->attributes->get('action')));

        return $bundle . '/' . $controller . '/' . $action;
    }

    /**
     * @param string $dashedWord
     *
     * @return array|string
     */
    private function dashToCamelCase($dashedWord)
    {
        $filter = new DashToCamelCase();

        return $filter->filter($dashedWord);
    }
}
