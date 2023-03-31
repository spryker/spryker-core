<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 * @method \Spryker\Zed\Store\Communication\StoreCommunicationFactory getFactory()
 * @method \Spryker\Zed\Store\Business\StoreFacadeInterface getFacade()
 * @method \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface getQueryContainer()
 */
class StoreBackendGatewayApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    /**
     * @var string
     */
    protected const SERVICE_STORE = 'store';

    /**
     * @var string
     */
    protected const SERVICE_ZED_REQUEST = 'service_zed_request';

    /**
     * {@inheritDoc}
     * - Provides store service.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_STORE, function (ContainerInterface $container): string {
            /** @var \Spryker\Shared\ZedRequest\Client\AbstractRequest $zedRequest */
            $zedRequest = $container->get(static::SERVICE_ZED_REQUEST);

            /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer */
            $storeTransfer = $zedRequest->getMetaTransfer('store');

            return $storeTransfer->getNameOrFail();
        });

        return $container;
    }
}
