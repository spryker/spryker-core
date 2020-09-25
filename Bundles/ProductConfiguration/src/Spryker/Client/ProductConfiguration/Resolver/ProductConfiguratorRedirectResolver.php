<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Resolver;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCurrencyClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToLocaleInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToStoreClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToPriceClientInterface;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCustomerClientInterface;

class ProductConfiguratorRedirectResolver implements ProductConfiguratorRedirectResolverInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface[]
     */
    protected $productConfiguratorRequestPlugins;

    /**
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface
     */
    protected $productConfiguratorRequestDefaultPlugin;

    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToLocaleInterface
     */
    protected $localeClient;

    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @param \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface[] $productConfiguratorRequestPlugins
     * @param \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface $productConfiguratorRequestDefaultPlugin
     * @param \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCustomerClientInterface $customerClient
     * @param \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToStoreClientInterface $storeClient
     * @param \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToLocaleInterface $localeClient
     * @param \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToPriceClientInterface $priceClient
     * @param \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCurrencyClientInterface $currencyClient
     */
    public function __construct(
        array $productConfiguratorRequestPlugins,
        ProductConfiguratorRequestPluginInterface $productConfiguratorRequestDefaultPlugin,
        ProductConfigurationToCustomerClientInterface $customerClient,
        ProductConfigurationToStoreClientInterface $storeClient,
        ProductConfigurationToLocaleInterface $localeClient,
        ProductConfigurationToPriceClientInterface $priceClient,
        ProductConfigurationToCurrencyClientInterface $currencyClient
    ) {
        $this->productConfiguratorRequestPlugins = $productConfiguratorRequestPlugins;
        $this->productConfiguratorRequestDefaultPlugin = $productConfiguratorRequestDefaultPlugin;
        $this->customerClient = $customerClient;
        $this->storeClient = $storeClient;
        $this->localeClient = $localeClient;
        $this->priceClient = $priceClient;
        $this->currencyClient = $currencyClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function prepareProductConfiguratorRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        $productConfigurationRequestDataTransfer = $this->expandProductConfigurationData(
            $productConfiguratorRequestTransfer->getProductConfiguratorRequestData()
        );

        $productConfiguratorRequestTransfer->setProductConfiguratorRequestData($productConfigurationRequestDataTransfer);

        foreach ($this->productConfiguratorRequestPlugins as $configuratorKey => $productConfiguratorRequestPlugin) {
            if ($configuratorKey === $productConfiguratorRequestTransfer->getProductConfiguratorRequestData()->getConfiguratorKey()) {
                return $productConfiguratorRequestPlugin->resolveProductConfiguratorRedirect($productConfiguratorRequestTransfer);
            }
        }

        return $this->productConfiguratorRequestDefaultPlugin
            ->resolveProductConfiguratorRedirect($productConfiguratorRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    protected function expandProductConfigurationData(
        ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
    ): ProductConfiguratorRequestDataTransfer {
        $productConfiguratorRequestDataTransfer->setCustomerReference(
            $this->customerClient->getCustomer()->getCustomerReference()
        );

        $productConfiguratorRequestDataTransfer->setStoreName(
            $this->storeClient->getCurrentStore()->getName()
        );

        $productConfiguratorRequestDataTransfer->setLocaleName(
            $this->localeClient->getCurrentLocale()
        );

        $productConfiguratorRequestDataTransfer->setPriceMode(
            $this->priceClient->getCurrentPriceMode()
        );

        $productConfiguratorRequestDataTransfer->setCurrencyCode(
            $this->currencyClient->getCurrent()->getCode()
        );

        return $productConfiguratorRequestDataTransfer;
    }
}
