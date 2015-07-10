<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel\Communication\Controller;

use SprykerEngine\Shared\Kernel\Communication\BundleControllerActionInterface;
use SprykerEngine\Shared\Kernel\Communication\RouteNameResolverInterface;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;

class BundleControllerActionRouteNameResolver implements RouteNameResolverInterface
{

    /**
     * @var BundleControllerActionInterface
     */
    private $bundleControllerAction;

    /**
     * @var CamelCaseToDash
     */
    private $filter;

    /**
     * @param BundleControllerActionInterface $bundleControllerAction
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
     * @return CamelCaseToDash
     */
    private function getFilter()
    {
        if (is_null($this->filter)) {
            $filter = new FilterChain();
            $filter->attach(new CamelCaseToDash());
            $filter->attach(new StringToLower());

            $this->filter = $filter;
        }

        return $this->filter;
    }

}
