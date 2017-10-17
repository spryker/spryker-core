<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Product\Storage;

interface ProductConcreteStorageInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @return mixed
     */
    public function getProductConcreteById($idProductConcrete);

    /**
     * @param array $idProductConcreteCollection
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer[]
     */
    public function getProductConcreteCollection(array $idProductConcreteCollection);
}
