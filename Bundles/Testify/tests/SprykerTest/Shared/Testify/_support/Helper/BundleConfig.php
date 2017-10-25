<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Spryker\Shared\Kernel\AbstractBundleConfig;
use Spryker\Shared\Kernel\BundleConfigMock\BundleConfigMock;

class BundleConfig extends Module
{
    /**
     * @var \Spryker\Shared\Kernel\BundleConfigMock\BundleConfigMock
     */
    private $bundleConfigMock;

    /**
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param null|array $config
     */
    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);

        $this->bundleConfigMock = new BundleConfigMock();
    }

    /**
     * @param \Spryker\Shared\Kernel\AbstractBundleConfig $bundleConfig
     *
     * @return void
     */
    public function addBundleConfigMock(AbstractBundleConfig $bundleConfig)
    {
        $this->bundleConfigMock->addBundleConfigMock($bundleConfig);
    }

    /**
     * @param \Spryker\Shared\Kernel\AbstractBundleConfig $bundleConfig
     *
     * @return bool
     */
    public function hasBundleConfigMock(AbstractBundleConfig $bundleConfig)
    {
        return $this->bundleConfigMock->hasBundleConfigMock($bundleConfig);
    }

    /**
     * @param \Spryker\Shared\Kernel\AbstractBundleConfig $bundleConfig
     *
     * @return \Spryker\Shared\Kernel\AbstractBundleConfig
     */
    public function getBundleConfigMock(AbstractBundleConfig $bundleConfig)
    {
        return $this->bundleConfigMock->getBundleConfigMock($bundleConfig);
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->bundleConfigMock->reset();
    }
}
