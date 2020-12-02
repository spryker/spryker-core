<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductStorage\Expander;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\MerchantProductStorage\Reader\MerchantProductStorageReaderInterface;

class MerchantProductStorageExpander implements MerchantProductStorageExpanderInterface
{
    protected const SELECTED_ATTRIBUTE_MERCHANT_REFERENCE = 'merchant_reference';
    protected const PARAM_SELECTED_MERCHANT_REFERENCE = 'selected_merchant_reference';
    protected const PARAM_SELECTED_MERCHANT_REFERENCE_TYPE = 'selected_merchant_reference_type';

    /**
     * @var \Spryker\Client\MerchantProductStorage\Reader\MerchantProductStorageReaderInterface
     */
    protected $merchantProductStorageReader;

    /**
     * @param \Spryker\Client\MerchantProductStorage\Reader\MerchantProductStorageReaderInterface $merchantProductStorageReader
     */
    public function __construct(MerchantProductStorageReaderInterface $merchantProductStorageReader)
    {
        $this->merchantProductStorageReader = $merchantProductStorageReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        $productSelectedAttributes = $productViewTransfer->getSelectedAttributes();
        $selectedMerchantReference = $this->findMerchantReferenceInSelectedAttributes($productSelectedAttributes);

        if (!$selectedMerchantReference) {
            return $productViewTransfer;
        }

        $merchantProductStorageTransfer = $this->merchantProductStorageReader->findOne(
            $productViewTransfer->getIdProductAbstract()
        );

        if (!$merchantProductStorageTransfer) {
            return $productViewTransfer;
        }

        if ($merchantProductStorageTransfer->getMerchantReference() !== $selectedMerchantReference) {
            return $productViewTransfer;
        }

        return $productViewTransfer->setMerchantReference($merchantProductStorageTransfer->getMerchantReference());
    }

    /**
     * @phpstan-param array<mixed> $selectedAttributes
     *
     * @param array $selectedAttributes
     *
     * @return string|null
     */
    protected function findMerchantReferenceInSelectedAttributes(array $selectedAttributes): ?string
    {
        if (!isset($selectedAttributes[static::PARAM_SELECTED_MERCHANT_REFERENCE_TYPE])) {
            return null;
        }

        if ($selectedAttributes[static::PARAM_SELECTED_MERCHANT_REFERENCE_TYPE] !== static::SELECTED_ATTRIBUTE_MERCHANT_REFERENCE) {
            return null;
        }

        if (!isset($selectedAttributes[static::PARAM_SELECTED_MERCHANT_REFERENCE])) {
            return null;
        }

        return $selectedAttributes[static::PARAM_SELECTED_MERCHANT_REFERENCE];
    }
}
