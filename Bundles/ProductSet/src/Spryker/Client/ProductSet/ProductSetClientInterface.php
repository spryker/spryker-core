<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSet;

/**
 * @method \Spryker\Client\ProductSet\ProductSetFactory getFactory()
 */
interface ProductSetClientInterface
{

    /**
     * Specification:
     * - Returns a list of Product Sets from Search.
     * - The results are sorted by weight descending.
     *
     * @api
     *
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function getProductSetList($limit = null, $offset = null);

    /**
     * Specification:
     * - Maps raw product set storage data to transfer object.
     * - The "images" property will contain the images of the default image set if available, or the first available
     * image set otherwise.
     *
     * @api
     *
     * @param array $productSetStorageData
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer
     */
    public function mapProductSetStorageDataToTransfer(array $productSetStorageData);

    /**
     * Specification:
     *  - Reads product set data from yves storage
     *  - Maps data to transfer
     *
     * @api
     *
     * @param int $idProductSet
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer|null
     */
    public function findProductSetByIdProductSet($idProductSet);

}
