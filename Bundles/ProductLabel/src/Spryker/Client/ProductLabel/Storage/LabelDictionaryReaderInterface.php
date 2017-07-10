<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabel\Storage;

interface LabelDictionaryReaderInterface
{

    /**
     * @param int[] $idsProductLabel
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer[]
     */
    public function findSortedLabelsByIdsProductLabel(array $idsProductLabel, $localeName);

    /**
     * @param int $idProductLabel
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer|null
     */
    public function findStorageProductLabelByIdProductLabel($idProductLabel, $localeName);

    /**
     * @param string $productName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer|null
     */
    public function findStorageProductLabelByName($productName, $localeName);

}
