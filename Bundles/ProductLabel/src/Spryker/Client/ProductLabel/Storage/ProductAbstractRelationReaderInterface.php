<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabel\Storage;

interface ProductAbstractRelationReaderInterface
{
    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer[]
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName);
}
