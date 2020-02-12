<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

interface ProductLabelEntityManagerInterface
{
    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    public function deleteProductLabel(int $idProductLabel): void;

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    public function deleteProductLabelStoreRelations(int $idProductLabel): void;

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    public function deleteProductLabelLocalizedAttributes(int $idProductLabel): void;

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    public function deleteProductLabelProductAbstractRelations(int $idProductLabel): void;

    /**
     * @param array $idStores
     * @param int $idProductLabel
     *
     * @return void
     */
    public function removeProductLabelStoreRelationsForStores(array $idStores, int $idProductLabel): void;

    /**
     * @param array $idStores
     * @param int $idProductLabel
     *
     * @return void
     */
    public function addProductLabelStoreRelationsForStores(array $idStores, int $idProductLabel): void;
}
