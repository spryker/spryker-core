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
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

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
            if ($localeTransfer->getLocaleNameOrFail() !== $currentLocale) {
                continue;
            }

            $productAbstractTransfer->addLocalizedAttributes(
                (new LocalizedAttributesTransfer())
                    ->setLocale($localeTransfer)
                    ->setName($productAbstractTransfer->getName())
            );
        }

        return $productAbstractTransfer;
    }
}
