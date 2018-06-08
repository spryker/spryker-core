<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeListTransfer;
use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductAlternativeFacadeInterface
{
    /**
     * Specification:
     * - Creates alternative abstract product for concrete one.
     * - Uses $idProductAbstract to find for which concrete product alternative one will be created.
     * - Uses $idProductAbstractAlternative as a reference to abstract product that will be an alternative one.
     * - Returns response transfer object with created product alternative.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idProductAbstractAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function createProductAbstractAlternative(int $idProductAbstract, int $idProductAbstractAlternative): ProductAlternativeResponseTransfer;

    /**
     * Specification:
     * - Creates alternative concrete product for concrete one.
     * - Uses $idProductConcrete to find for which concrete product alternative one will be created.
     * - Uses $idProductAbstractAlternative as a reference to abstract product that will be an alternative one.
     * - Returns response transfer object with created product alternative.
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idProductConcreteAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function createProductConcreteAlternative(int $idProductConcrete, int $idProductConcreteAlternative): ProductAlternativeResponseTransfer;

    /**
     * Specification:
     * - Retrieve all alternative concrete products for concrete product.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativesByIdProductConcrete(int $idProductConcrete): ProductAlternativeCollectionTransfer;

    /**
     * Specification:
     * - Retrieve product alternative by id.
     *
     * @api
     *
     * @param int $idProductAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function getProductAlternativeByIdProductAlternative(int $idProductAlternative): ProductAlternativeTransfer;

    /**
     * Specification:
     * - Searches for all alternatives for concrete product and returns an array, hydrated with data.
     * - Uses ProductAlternativeReader to find all product alternatives.
     * - Uses ProductAlternativeListHydrator to create ProductAlternativeList with data.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListTransfer
     */
    public function getProductAlternativeListByIdProductConcrete(int $idProductConcrete): ProductAlternativeListTransfer;

    /**
     * Specification:
     * - Deletes product alternative by id.
     *
     * @api
     *
     * @param int $idProductAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function deleteProductAlternativeByIdProductAlternativeResponse(int $idProductAlternative): ProductAlternativeResponseTransfer;

    /**
     * Specification:
     * - Persists product alternatives stored in product concrete transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductAlternatives(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;
}
