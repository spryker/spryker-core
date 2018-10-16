<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage;

use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;

interface ProductLabelStorageClientInterface
{
    /**
     * Specification:
     * - TODO: add specification
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName);

    /**
     * Specification:
     * - TODO: add specification
     *
     * @api
     *
     * @param array $idProductLabels
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabels(array $idProductLabels, $localeName);

    /**
     * Specification:
     * - TODO: add specification
     *
     * @api
     *
     * @deprecated use findLabelByKey() instead.
     *
     * @param string $labelName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByName($labelName, $localeName);

    /**
     * Specification:
     * - Retrieves ProductLabelDictionaryItemTransfer by label key.
     *
     * @api
     *
     * @param string $labelKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByKey(string $labelKey, string $localeName): ?ProductLabelDictionaryItemTransfer;
}
