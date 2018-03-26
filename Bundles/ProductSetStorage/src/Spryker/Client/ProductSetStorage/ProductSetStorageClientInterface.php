<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetStorage;

interface ProductSetStorageClientInterface
{
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
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer
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
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer|null
     */
    public function getProductSetByIdProductSet($idProductSet, $localeName);
}
