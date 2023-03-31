<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Currency\Communication\CurrencyCommunicationFactory getFactory()
 * @method \Spryker\Zed\Currency\Business\CurrencyFacadeInterface getFacade()
 * @method \Spryker\Zed\Currency\CurrencyConfig getConfig()
 */
class CurrencyBackendGatewayApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    /**
     * @var string
     */
    protected const SERVICE_CURRENCY = 'currency';

    /**
     * @var string
     */
    protected const SERVICE_ZED_REQUEST = 'service_zed_request';

    /**
     * {@inheritDoc}
     * - Provides currency service.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_CURRENCY, function (ContainerInterface $container): string {
            /** @var \Spryker\Shared\ZedRequest\Client\AbstractRequest $zedRequest */
            $zedRequest = $container->get(static::SERVICE_ZED_REQUEST);

            /** @var \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer */
            $currencyTransfer = $zedRequest->getMetaTransfer('currency');

            return $currencyTransfer->getCodeOrFail();
        });

        return $container;
    }
}
