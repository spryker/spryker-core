<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business;

use Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer;

interface ProductAlternativeFacadeInterface
{
    /**
     * TODO: Replace transfer that is returned to proper one
     * TODO: Review this specification again, when code will be finished
     *
     * Specification:
     * - Creates alternative abstract product for concrete one
     * - Uses $idProduct to find for which concrete product alternative one will be created
     * - Uses $idProductAbstractAlternative as a reference to abstract product that will be an alternative one
     * - Returns transfer object of created alternative product
     *
     * @api
     *
     * @param int $idProduct
     * @param int $idProductAbstractAlternative
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer
     */
    public function createProductAbstractAlternative(int $idProduct, int $idProductAbstractAlternative): SpyProductAlternativeEntityTransfer;

    /**
     * TODO: Replace transfer that is returned to proper one
     * TODO: Review this specification again, when code will be finished
     *
     * Specification:
     * - Creates alternative concrete product for concrete one
     * - Uses $idProduct to find for which concrete product alternative one will be created
     * - Uses $idProductAbstractAlternative as a reference to abstract product that will be an alternative one
     * - Returns transfer object of created alternative product
     *
     * @api
     *
     * @param int $idProduct
     * @param int $idProductConcreteAlternative
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer
     */
    public function createProductConcreteAlternative(int $idProduct, int $idProductConcreteAlternative): SpyProductAlternativeEntityTransfer;
}
