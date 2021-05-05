<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Expander;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;

class ProductAbstractLocalizedAttributesExpander implements ProductAbstractLocalizedAttributesExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface
     */
    protected $localeDataProvider;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface $localeDataProvider
     */
    public function __construct(
        ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        LocaleDataProviderInterface $localeDataProvider
    ) {
        $this->localeFacade = $localeFacade;
        $this->localeDataProvider = $localeDataProvider;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function expandLocalizedAttributes(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        $defaultStoreDefaultLocale = $this->localeDataProvider->findDefaultStoreDefaultLocale();

        foreach ($localeTransfers as $localeTransfer) {
            $productAbstractLocalizedName = $localeTransfer->getLocaleNameOrFail() === $defaultStoreDefaultLocale
                ? $productAbstractTransfer->getName()
                : '';
            $productAbstractTransfer->addLocalizedAttributes(
                (new LocalizedAttributesTransfer())
                    ->setLocale($localeTransfer)
                    ->setName($productAbstractLocalizedName)
            );
        }

        return $productAbstractTransfer;
    }
}
