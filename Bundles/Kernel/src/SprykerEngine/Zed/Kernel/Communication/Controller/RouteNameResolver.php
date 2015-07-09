<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Communication\Controller;

use SprykerEngine\Shared\Kernel\Communication\RouteNameResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Zend\Filter\Word\DashToCamelCase;

class RouteNameResolver implements RouteNameResolverInterface
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request $request
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
     * @param $dashedWord
     *
     * @return array|string
     */
    private function dashToCamelCase($dashedWord)
    {
        $filter = new DashToCamelCase();

        return $filter->filter($dashedWord);
    }

}
