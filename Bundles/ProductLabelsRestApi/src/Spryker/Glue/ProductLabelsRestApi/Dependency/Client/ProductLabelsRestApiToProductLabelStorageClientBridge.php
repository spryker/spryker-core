<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi\Dependency\Client;

class ProductLabelsRestApiToProductLabelStorageClientBridge implements ProductLabelsRestApiToProductLabelStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductLabelStorage\ProductLabelStorageClientInterface
     */
    protected $productLabelStorageClient;

    /**
     * @param \Spryker\Client\ProductLabelStorage\ProductLabelStorageClientInterface $productLabelStorageClient
     */
    public function __construct($productLabelStorageClient)
    {
        $this->productLabelStorageClient = $productLabelStorageClient;
    }

    /**
     * @param array $idProductLabels
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabels(array $idProductLabels, $localeName, string $storeName): array
    {
        return $this->productLabelStorageClient->findLabels($idProductLabels, $localeName, $storeName);
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName, string $storeName): array
    {
        return $this->productLabelStorageClient
            ->findLabelsByIdProductAbstract($idProductAbstract, $localeName, $storeName);
    }

    /**
     * @param int[] $productAbstractIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[][]
     */
    public function getProductLabelsByProductAbstractIds(
        array $productAbstractIds,
        string $localeName,
        string $storeName
    ): array {
        return $this->productLabelStorageClient
            ->getProductLabelsByProductAbstractIds($productAbstractIds, $localeName, $storeName);
    }
}
