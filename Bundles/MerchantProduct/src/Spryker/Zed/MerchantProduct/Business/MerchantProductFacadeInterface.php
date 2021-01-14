<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MerchantProductCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductAbstractResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;

interface MerchantProductFacadeInterface
{
    /**
     * Specification:
     * - Finds merchant for the given abstract product id.
     * - Returns found MerchantTransfer or null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchant(MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer): ?MerchantTransfer;

    /**
     * Specification:
     * - Finds merchant products by provided MerchantProductCriteria.
     * - Returns MerchantProductCollection transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductCollectionTransfer
     */
    public function get(MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer): MerchantProductCollectionTransfer;

    /**
     * Specifications:
     * - Validates that merchant references in the cart items match existing merchant products.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateCartChange(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;

    /**
     * Specification:
     * - Finds merchant product by provided MerchantProductCriteria and returns corresponding abstract product.
     * - Returns null if merchant product not found.
     * - Returns null if abstract product not found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstract(
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): ?ProductAbstractTransfer;

    /**
     * Specification:
     * - Updates abstract product if it belongs to merchant.
     * - Returns ProductAbstractResponseTransfer.isSuccessful = true on successful update.
     * - Returns ProductAbstractResponseTransfer.isSuccessful = false and corresponding error if product doesn't belong to merchant.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractResponseTransfer
     */
    public function updateProductAbstract(MerchantProductTransfer $merchantProductTransfer): ProductAbstractResponseTransfer;

    /**
     * Specification:
     * - Requires merchant ID.
     * - Returns the list of concrete products related to the provided merchant by checking abstract product - merchant relationship and criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductConcreteCollection(
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): ProductConcreteCollectionTransfer;
}
