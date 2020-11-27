<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductStorage\Plugin\ProductStorage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductViewExpanderPluginInterface;

/**
 * @method \Spryker\Client\MerchantProductStorage\MerchantProductStorageFactory getFactory()
 */
class ProductViewMerchantProductExpanderPlugin extends AbstractPlugin implements ProductViewExpanderPluginInterface
{
    protected const SELECTED_ATTRIBUTE_MERCHANT_REFERENCE = 'merchant_reference';

    /**
     * @uses \SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReader::PARAM_SELECTED_MERCHANT_REFERENCE
     */
    protected const PARAM_SELECTED_MERCHANT_REFERENCE = 'selected_merchant_reference';

    /**
     * @uses \SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReader
     */
    protected const PARAM_SELECTED_MERCHANT_REFERENCE_TYPE = 'selected_merchant_reference_type';

    /**
     * {@inheritDoc}
     * - Expands ProductView transfer object with merchant reference.
     *
     * @api
     *
     * @phpstan-param array<mixed> $productData
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(
        ProductViewTransfer $productViewTransfer,
        array $productData,
        $localeName
    ): ProductViewTransfer {
        $productSelectedAttributes = $productViewTransfer->getSelectedAttributes();

        $selectedMerchantReference = isset($productSelectedAttributes[static::PARAM_SELECTED_MERCHANT_REFERENCE_TYPE])
            && $productSelectedAttributes[static::PARAM_SELECTED_MERCHANT_REFERENCE_TYPE] === static::SELECTED_ATTRIBUTE_MERCHANT_REFERENCE
            && isset($productSelectedAttributes[static::PARAM_SELECTED_MERCHANT_REFERENCE]) ? $productSelectedAttributes[static::PARAM_SELECTED_MERCHANT_REFERENCE] : null;

        if (!$selectedMerchantReference) {
            return $productViewTransfer;
        }

        $merchantProductStorageTransfer = $this->getFactory()
            ->createMerchantProductStorageReader()
            ->findOne($productViewTransfer->getIdProductAbstract());

        if (!$merchantProductStorageTransfer) {
            return $productViewTransfer;
        }

        if ($merchantProductStorageTransfer->getMerchantReference() !== $selectedMerchantReference) {
            return $productViewTransfer;
        }

        return $productViewTransfer->setMerchantReference($merchantProductStorageTransfer->getMerchantReference());
    }
}
