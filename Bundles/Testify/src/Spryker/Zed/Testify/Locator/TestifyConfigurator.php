<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Locator;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Shared\Testify\Config\TestifyConfigInterface;
use Spryker\Shared\Testify\Locator\TestifyConfiguratorInterface;
use Spryker\Zed\Kernel\Container;

class TestifyConfigurator implements TestifyConfiguratorInterface
{
    /**
     * @var \Spryker\Zed\Kernel\Container
     */
    protected $container;

    /**
     * @var \Spryker\Shared\Testify\Config\TestifyConfigInterface
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     * @param \Spryker\Shared\Testify\Config\TestifyConfigInterface $config
     */
    public function __construct(Container $container, TestifyConfigInterface $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return $this
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return \Spryker\Shared\Testify\Config\TestifyConfigInterface
     */
    public function getConfig()
    {
        return $this->config;
    }
}
