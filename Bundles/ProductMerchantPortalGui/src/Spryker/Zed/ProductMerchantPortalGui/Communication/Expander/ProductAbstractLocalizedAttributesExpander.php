<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Expander;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;

class ProductAbstractLocalizedAttributesExpander implements ProductAbstractLocalizedAttributesExpanderInterface
{
    /**
     * @var string
     */
    protected const DEFAULT_PRODUCT_NAME = '';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    protected ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function expandLocalizedAttributes(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        $currentLocale = $this->localeFacade->getCurrentLocale()->getLocaleNameOrFail();

        foreach ($localeTransfers as $localeTransfer) {
            $name = $localeTransfer->getLocaleNameOrFail() === $currentLocale
                ? $productAbstractTransfer->getName()
                : static::DEFAULT_PRODUCT_NAME;

            $productAbstractTransfer->addLocalizedAttributes(
                (new LocalizedAttributesTransfer())
                    ->setLocale($localeTransfer)
                    ->setName($name),
            );
        }

        return $productAbstractTransfer;
    }
}
