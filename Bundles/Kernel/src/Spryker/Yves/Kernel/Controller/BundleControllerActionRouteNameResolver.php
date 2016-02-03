<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Kernel\Controller;

use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;
use Spryker\Shared\Kernel\Communication\RouteNameResolverInterface;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;

class BundleControllerActionRouteNameResolver implements RouteNameResolverInterface
{

    /**
     * @var \Spryker\Shared\Kernel\Communication\BundleControllerActionInterface
     */
    private $bundleControllerAction;

    /**
     * @var \Zend\Filter\Word\CamelCaseToDash
     */
    private $filter;

    /**
     * @param \Spryker\Shared\Kernel\Communication\BundleControllerActionInterface $bundleControllerAction
     */
    public function __construct(BundleControllerActionInterface $bundleControllerAction)
    {
        $this->bundleControllerAction = $bundleControllerAction;
    }

    /**
     * @return string
     */
    public function resolve()
    {
        $bundle = $this->filter($this->bundleControllerAction->getBundle());
        $controller = $this->filter($this->bundleControllerAction->getController());
        $action = $this->filter($this->bundleControllerAction->getAction());

        return $bundle . '/' . $controller . '/' . $action;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    private function filter($string)
    {
        return $this->getFilter()->filter($string);
    }

    /**
     * @return \Zend\Filter\Word\CamelCaseToDash
     */
    private function getFilter()
    {
        if ($this->filter === null) {
            $filter = new FilterChain();
            $filter->attach(new CamelCaseToDash());
            $filter->attach(new StringToLower());

            $this->filter = $filter;
        }

        return $this->filter;
    }

}
