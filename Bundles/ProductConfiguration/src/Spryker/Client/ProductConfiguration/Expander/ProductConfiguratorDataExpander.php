<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Expander;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCurrencyClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCustomerClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToLocaleInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToPriceClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToStoreClientInterface;

class ProductConfiguratorDataExpander implements ProductConfiguratorDataExpanderInterface
{
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
     * @param \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCustomerClientInterface $customerClient
     * @param \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToStoreClientInterface $storeClient
     * @param \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToLocaleInterface $localeClient
     * @param \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToPriceClientInterface $priceClient
     * @param \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCurrencyClientInterface $currencyClient
     */
    public function __construct(
        ProductConfigurationToCustomerClientInterface $customerClient,
        ProductConfigurationToStoreClientInterface $storeClient,
        ProductConfigurationToLocaleInterface $localeClient,
        ProductConfigurationToPriceClientInterface $priceClient,
        ProductConfigurationToCurrencyClientInterface $currencyClient
    ) {
        $this->customerClient = $customerClient;
        $this->storeClient = $storeClient;
        $this->localeClient = $localeClient;
        $this->priceClient = $priceClient;
        $this->currencyClient = $currencyClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    public function expand(ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer): ProductConfiguratorRequestDataTransfer
    {
        $customerTransfer = $this->customerClient->getCustomer();

        if ($customerTransfer) {
            $productConfiguratorRequestDataTransfer->setCustomerReference(
                $customerTransfer->getCustomerReference()
            );
        }

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
