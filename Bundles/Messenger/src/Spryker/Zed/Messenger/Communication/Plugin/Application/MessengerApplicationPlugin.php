<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Messenger\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Messenger\Business\MessengerFacadeInterface getFacade()
 * @method \Spryker\Zed\Messenger\MessengerConfig getConfig()
 */
class MessengerApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    public const SERVICE_MESSENGER = 'messenger';

    /**
     * {@inheritdoc}
     * - Adds the Messenger service to the Container.
     *
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
        $container->set(static::SERVICE_MESSENGER, function () {
            return $this->getFacade();
        });

        return $container;
    }
}
