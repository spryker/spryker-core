<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Store\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\Store\StoreClientInterface getClient()
 */
class StoreApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    public const STORE = 'store';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addStore(ContainerInterface $container): ContainerInterface
    {
        $container[static::STORE] = function () {
            return $this->getStoreName();
        };

        return $container;
    }

    /**
     * @return string
     */
    protected function getStoreName(): string
    {
        return $this->getClient()->getCurrentStore()->getName();
    }
}
