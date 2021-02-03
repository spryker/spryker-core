<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Expander;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer\ProductConcreteDefaultProductOfferReaderInterface;
use Spryker\Shared\MerchantProductOfferStorage\MerchantProductOfferStorageConfig;

class ProductViewOfferExpander implements ProductViewOfferExpanderInterface
{
    protected const PARAM_SELECTED_MERCHANT_REFERENCE = 'selected_merchant_reference';
    protected const PARAM_SELECTED_MERCHANT_REFERENCE_TYPE = 'selected_merchant_reference_type';

    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer\ProductConcreteDefaultProductOfferReaderInterface
     */
    protected $productConcreteDefaultProductOfferReader;

    /**
     * @param \Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer\ProductConcreteDefaultProductOfferReaderInterface $productConcreteDefaultProductOfferReader
     */
    public function __construct(ProductConcreteDefaultProductOfferReaderInterface $productConcreteDefaultProductOfferReader)
    {
        $this->productConcreteDefaultProductOfferReader = $productConcreteDefaultProductOfferReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\ProductStorageCriteriaTransfer|null $productStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(
        ProductViewTransfer $productViewTransfer,
        ?ProductStorageCriteriaTransfer $productStorageCriteriaTransfer = null
    ): ProductViewTransfer {
        if (!$productStorageCriteriaTransfer) {
            return $productViewTransfer;
        }

        if (!$productViewTransfer->getIdProductConcrete()) {
            return $productViewTransfer;
        }

        $productOfferStorageCriteriaTransfer = (new ProductOfferStorageCriteriaTransfer())->fromArray(
            $productStorageCriteriaTransfer->modifiedToArray(),
            true
        );
        $productOfferStorageCriteriaTransfer->fromArray($productViewTransfer->toArray(), true);

        $selectedAttributes = $productViewTransfer->getSelectedAttributes();
        $selectedProductOfferReference = $this->findProductOfferReferenceInSelectedAttributes($selectedAttributes);

        $productOfferStorageCriteriaTransfer->setProductOfferReference($selectedProductOfferReference);
        $productOfferStorageCriteriaTransfer->addProductConcreteSku($productViewTransfer->getSku());

        return $productViewTransfer->setProductOfferReference(
            $this->productConcreteDefaultProductOfferReader->findProductOfferReference($productOfferStorageCriteriaTransfer)
        );
    }

    /**
     * @phpstan-param array<mixed> $selectedAttributes
     *
     * @param array $selectedAttributes
     *
     * @return string|null
     */
    protected function findProductOfferReferenceInSelectedAttributes(array $selectedAttributes): ?string
    {
        if (!isset($selectedAttributes[static::PARAM_SELECTED_MERCHANT_REFERENCE_TYPE])) {
            return null;
        }

        if ($selectedAttributes[static::PARAM_SELECTED_MERCHANT_REFERENCE_TYPE] !== MerchantProductOfferStorageConfig::PRODUCT_OFFER_REFERENCE_ATTRIBUTE) {
            return null;
        }

        if (!isset($selectedAttributes[static::PARAM_SELECTED_MERCHANT_REFERENCE])) {
            return null;
        }

        return $selectedAttributes[static::PARAM_SELECTED_MERCHANT_REFERENCE];
    }
}
