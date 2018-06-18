<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeListItemTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;

interface ProductAlternativeRepositoryInterface
{
    /**
     * Specification:
     * - Retrieves all alternative concrete products for concrete product with id = $idProductConcrete
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativesForProductConcrete(int $idProductConcrete): ProductAlternativeCollectionTransfer;

    /**
     * Specification:
     * - Retrieves product alternative object by concrete product id
     * - Uses $productAlternativeTransfer to extract concrete product id
     *
     * @api
     *
     * @param int $idProductAlternative
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function findProductAlternativeByIdProductAlternative(int $idProductAlternative): ?ProductAlternativeTransfer;

    /**
     * Specification:
     * - Collects all abstract product data for product alternative and map it to ProductAlternativeListItemTransfer.
     * - ProductAlternativeListItem transfer acts as a data row for Product Alternatives table view.
     * - Requires id and isActive values passed in $productAbstractTransfer.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    public function getProductAlternativeListItemTransferForProductAbstract(
        int $idProductAbstract,
        LocaleTransfer $localeTransfer
    ): ProductAlternativeListItemTransfer;

    /**
     * Specification:
     * - Collects all concrete product data for product alternative and map it to ProductAlternativeListItemTransfer.
     * - ProductAlternativeListItem transfer acts as a data row for Product Alternatives table view.
     * - Required id value passed in $productConcreteTransfer.
     *
     * @api
     *
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    public function getProductAlternativeListItemTransferForProductConcrete(
        int $idProduct,
        LocaleTransfer $localeTransfer
    ): ProductAlternativeListItemTransfer;
}
