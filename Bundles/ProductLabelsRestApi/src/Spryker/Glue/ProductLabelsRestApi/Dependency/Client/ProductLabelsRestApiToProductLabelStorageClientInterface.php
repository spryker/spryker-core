<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi\Dependency\Client;

interface ProductLabelsRestApiToProductLabelStorageClientInterface
{
    /**
     * @param array $idProductLabels
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>
     */
    public function findLabels(array $idProductLabels, $localeName, string $storeName);

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName, string $storeName);

    /**
     * @param array<int> $productAbstractIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>>
     */
    public function getProductLabelsByProductAbstractIds(
        array $productAbstractIds,
        string $localeName,
        string $storeName
    ): array;
}
