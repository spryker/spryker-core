<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel;

use Spryker\Shared\Kernel\ClassResolver\BundleNameResolver;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;
use Zend\Filter\Word\DashToCamelCase;

class BundleControllerAction implements BundleControllerActionInterface
{
    /**
     * @var string
     */
    private $bundle;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $action;

    /**
     * @var \Zend\Filter\Word\DashToCamelCase|null
     */
    private $filter;

    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\BundleNameResolver|null
     */
    protected $bundleNameResolver;

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     */
    public function __construct($bundle, $controller, $action)
    {
        $this->bundle = $bundle;
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function filter($value)
    {
        return lcfirst($this->getFilter()->filter($value));
    }

    /**
     * @return \Zend\Filter\Word\DashToCamelCase
     */
    private function getFilter()
    {
        if ($this->filter === null) {
            $this->filter = new DashToCamelCase();
        }

        return $this->filter;
    }

    /**
     * @return string
     */
    public function getBundle()
    {
        $bundleName = $this->filter($this->bundle);
        $bundleName = $this->getBundleNameResolver()->resolve($bundleName);

        return $bundleName;
    }

    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\BundleNameResolver
     */
    protected function getBundleNameResolver()
    {
        if (!$this->bundleNameResolver) {
            $this->bundleNameResolver = new BundleNameResolver();
        }

        return $this->bundleNameResolver;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->filter($this->controller);
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->filter($this->action);
    }
}
