<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business;

interface ProductDiscontinuedProductLabelConnectorFacadeInterface
{
    /**
     * Specification:
     *  - Installs label for discontinued products.
     *
     * @api
     *
     * @return void
     */
    public function installProductDiscontinuedProductLabelConnector(): void;

    /**
     * Specification:
     *  - Adds or removes label "Discontinued" if applicable.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return void
     */
    public function updateAbstractProductWithDiscontinuedLabel(int $idProduct): void;

    /**
     * Specification:
     *  - Removes label "Discontinued" if applicable.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return void
     */
    public function removeProductAbstractRelationsForLabel(int $idProduct): void;

    /**
     * Specification:
     * - Returns a list of Product Label - Product Abstract relation to assign and deassign.
     * - The relation changes are based on discontinuation of product.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findProductLabelProductAbstractRelationChanges(): array;

    /**
     * Specification:
     *  - Removes label "Discontinued" if applicable for all applicable products.
     *
     * @api
     *
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function removeProductAbstractRelationsForLabelInBulk(array $productConcreteIds): void;
}
