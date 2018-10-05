<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage\Storage;

use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;

interface LabelDictionaryReaderInterface
{
    /**
     * @param int[] $idsProductLabel
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findSortedLabelsByIdsProductLabel(array $idsProductLabel, $localeName);

    /**
     * @param int $idProductLabel
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByIdProductLabel($idProductLabel, $localeName);

    /**
     * @param string $labelName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByLocalizedName($labelName, $localeName);

    /**
     * @deprecated Use findLabelByKey($labelKey, $localeName): ?ProductLabelDictionaryItemTransfer.
     *
     * @param int $labelName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByName($labelName, $localeName);

    /**
     * @param $labelKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByKey($labelKey, $localeName): ?ProductLabelDictionaryItemTransfer;
}
