<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductLabelStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves labels collection for given abstract product ID, locale and store name.
     * - Only labels assigned with passed $storeName will be returned.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName, string $storeName);

    /**
     * Specification:
     * - Retrieves product labels by abstract product IDs and by locale and store.
     * - Returns array of ProductLabelDictionaryItemTransfers indexed by id of product abstract.
     * - Only labels assigned with passed $storeName will be returned.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[][]
     */
    public function getProductLabelsByProductAbstractIds(array $productAbstractIds, string $localeName, string $storeName): array;

    /**
     * Specification:
     * - Retrieves labels collection for the given list of labels IDs, locale and store name.
     * - Only labels assigned with passed $storeName will be returned.
     *
     * @api
     *
     * @param array $idProductLabels
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabels(array $idProductLabels, $localeName, string $storeName);

    /**
     * Specification:
     * - Retrieves label for given label name, locale and store name.
     * - Only labels assigned with passed $storeName will be returned.
     *
     * @api
     *
     * @param string $labelName
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByName($labelName, $localeName, string $storeName);

    /**
     * Specification:
     * - Expands ProductViewTransfer with product labels.
     * - Requires ProductViewTransfer.idProductAbstract to be set.
     * - Only labels assigned with passed $storeName and $localeName will be used for expansion.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductView(
        ProductViewTransfer $productViewTransfer,
        string $localeName,
        string $storeName
    ): ProductViewTransfer;
}
