<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Locator\Business;

use Spryker\Service\Kernel\ServiceLocator;
use Spryker\Zed\Kernel\Business\FacadeLocator;
use Spryker\Zed\Kernel\Persistence\QueryContainerLocator;
use Spryker\Zed\Testify\Locator\AbstractLocator;
use Closure;

class BusinessLocator extends AbstractLocator
{

    /**
     * @var array
     */
    private $projectNamespaces = [];

    /**
     * @var array
     */
    private $coreNamespaces = [];

    /**
     * @var Closure
     */
    private $closure;

    /**
     * @param array $projectNamespaces
     * @param array $coreNamespaces
     * @param \Closure $closure
     */
    public function __construct(array $projectNamespaces, array $coreNamespaces, Closure $closure)
    {
        $this->projectNamespaces = $projectNamespaces;
        $this->coreNamespaces = $coreNamespaces;
        $this->closure = $closure;
    }

    /**
     * @return \Spryker\Shared\Kernel\BundleProxy
     */
    protected function getBundleProxy()
    {
        $locators = [
            new FacadeLocator(),
            new QueryContainerLocator(),
            new ServiceLocator(),
        ];

        $bundleProxy = new BundleProxy($this);
        $bundleProxy
            ->setProjectNamespaces($this->projectNamespaces)
            ->setCoreNamespaces($this->coreNamespaces)
            ->setClosure($this->closure)
            ->setLocator($locators);

        return $bundleProxy;
    }


}
