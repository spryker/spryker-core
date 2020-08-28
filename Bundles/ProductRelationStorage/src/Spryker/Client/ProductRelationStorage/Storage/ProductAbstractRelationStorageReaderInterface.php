<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelationStorage\Storage;

interface ProductAbstractRelationStorageReaderInterface
{
    /**
     * @param int $idProductAbstract
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractRelationStorageTransfer|null
     */
    public function findProductAbstractRelation(int $idProductAbstract, string $storeName);
}
