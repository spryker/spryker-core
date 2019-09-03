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
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabels(array $idProductLabels, $localeName): array
    {
        return $this->productLabelStorageClient->findLabels($idProductLabels, $localeName);
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName): array
    {
        return $this->productLabelStorageClient->findLabelsByIdProductAbstract($idProductAbstract, $localeName);
    }

    /**
     * @param int[] $productAbstractIds
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[][]
     */
    public function getLabelsByProductAbstractIds(array $productAbstractIds, string $localeName): array
    {
        return $this->productLabelStorageClient->getLabelsByProductAbstractIds($productAbstractIds, $localeName);
    }
}
