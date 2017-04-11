<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Locator\Business;

use Spryker\Client\Kernel\ClientLocator;
use Spryker\Service\Kernel\ServiceLocator;
use Spryker\Zed\Kernel\Business\FacadeLocator;
use Spryker\Zed\Kernel\Persistence\QueryContainerLocator;
use Spryker\Zed\Testify\Locator\AbstractLocator;

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
     * @param array $projectNamespaces
     * @param array $coreNamespaces
     */
    public function __construct(array $projectNamespaces, array $coreNamespaces)
    {
        $this->projectNamespaces = $projectNamespaces;
        $this->coreNamespaces = $coreNamespaces;
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
            new ClientLocator(),
        ];

        $bundleProxy = new BundleProxy($this);
        $bundleProxy
            ->setProjectNamespaces($this->projectNamespaces)
            ->setCoreNamespaces($this->coreNamespaces)
            ->setLocator($locators);

        return $bundleProxy;
    }

}
