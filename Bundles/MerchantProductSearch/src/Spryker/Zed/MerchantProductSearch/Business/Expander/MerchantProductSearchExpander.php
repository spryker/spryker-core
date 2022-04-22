<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Business\Expander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToMerchantProductFacadeInterface;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;

class MerchantProductSearchExpander implements MerchantProductSearchExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToMerchantProductFacadeInterface
     */
    protected $merchantProductFacade;

    /**
     * @param \Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToMerchantProductFacadeInterface $merchantProductFacade
     */
    public function __construct(MerchantProductSearchToMerchantProductFacadeInterface $merchantProductFacade)
    {
        $this->merchantProductFacade = $merchantProductFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param array $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function expandProductConcretePageMap(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ): PageMapTransfer {
        if (!$productData[ProductConcretePageSearchTransfer::FK_PRODUCT]) {
            return $pageMapTransfer;
        }

        $merchantProductCriteriaTransfer = (new MerchantProductCriteriaTransfer())
            ->addIdProductConcrete($productData[ProductConcretePageSearchTransfer::FK_PRODUCT]);
        $merchantTransfer = $this->merchantProductFacade->findMerchant($merchantProductCriteriaTransfer);

        if (!$merchantTransfer) {
            return $pageMapTransfer;
        }

        $pageMapTransfer->addMerchantReference($merchantTransfer->getMerchantReference());

        return $pageMapTransfer;
    }
}
