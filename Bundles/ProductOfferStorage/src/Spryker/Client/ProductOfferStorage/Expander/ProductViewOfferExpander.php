<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferStorage\Expander;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductOfferStorage\Reader\ProductConcreteDefaultProductOfferReaderInterface;

class ProductViewOfferExpander implements ProductViewOfferExpanderInterface
{
    /**
     * @var string
     */
    protected const PARAM_SELECTED_REFERENCE = 'selected_merchant_reference';

    /**
     * @var string
     */
    protected const PARAM_SELECTED_REFERENCE_TYPE = 'selected_merchant_reference_type';

    /**
     * @uses \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::PRODUCT_OFFER_REFERENCE_ATTRIBUTE
     *
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_ATTRIBUTE = 'product_offer_reference';

    /**
     * @var \Spryker\Client\ProductOfferStorage\Reader\ProductConcreteDefaultProductOfferReaderInterface
     */
    protected $productConcreteDefaultProductOfferReader;

    /**
     * @param \Spryker\Client\ProductOfferStorage\Reader\ProductConcreteDefaultProductOfferReaderInterface $productConcreteDefaultProductOfferReader
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
            true,
        );
        $productOfferStorageCriteriaTransfer->fromArray($productViewTransfer->toArray(), true);

        $selectedAttributes = $productViewTransfer->getSelectedAttributes();
        $selectedProductOfferReference = $this->findProductOfferReferenceInSelectedAttributes($selectedAttributes);

        $productOfferStorageCriteriaTransfer->setProductOfferReference($selectedProductOfferReference);

        /** @var string $sku */
        $sku = $productViewTransfer->getSku();
        $productOfferStorageCriteriaTransfer->addProductConcreteSku($sku);

        return $productViewTransfer->setProductOfferReference(
            $this->productConcreteDefaultProductOfferReader->findProductOfferReference($productOfferStorageCriteriaTransfer),
        );
    }

    /**
     * @param array<mixed> $selectedAttributes
     *
     * @return string|null
     */
    protected function findProductOfferReferenceInSelectedAttributes(array $selectedAttributes): ?string
    {
        if (!isset($selectedAttributes[static::PARAM_SELECTED_REFERENCE_TYPE])) {
            return null;
        }

        if ($selectedAttributes[static::PARAM_SELECTED_REFERENCE_TYPE] !== static::PRODUCT_OFFER_REFERENCE_ATTRIBUTE) {
            return null;
        }

        if (!isset($selectedAttributes[static::PARAM_SELECTED_REFERENCE])) {
            return null;
        }

        return $selectedAttributes[static::PARAM_SELECTED_REFERENCE];
    }
}
