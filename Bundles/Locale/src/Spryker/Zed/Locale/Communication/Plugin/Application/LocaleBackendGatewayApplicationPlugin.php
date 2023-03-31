<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Locale\Communication\LocaleCommunicationFactory getFactory()
 * @method \Spryker\Zed\Locale\LocaleConfig getConfig()
 * @method \Spryker\Zed\Locale\Business\LocaleFacadeInterface getFacade()
 */
class LocaleBackendGatewayApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    /**
     * @var string
     */
    protected const SERVICE_LOCALE = 'locale';

    /**
     * @var string
     */
    protected const SERVICE_ZED_REQUEST = 'service_zed_request';

    /**
     * {@inheritDoc}
     * - Provides locale service.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_LOCALE, function (ContainerInterface $container): string {
            /** @var \Spryker\Shared\ZedRequest\Client\AbstractRequest $zedRequest */
            $zedRequest = $container->get(static::SERVICE_ZED_REQUEST);

            /** @var \Generated\Shared\Transfer\LocaleTransfer $localeTransfer */
            $localeTransfer = $zedRequest->getMetaTransfer('locale');

            return $localeTransfer->getLocaleNameOrFail();
        });

        return $container;
    }
}
