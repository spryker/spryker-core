<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;

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
     * @param string $labelKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByKey(string $labelKey, string $localeName): ?ProductLabelDictionaryItemTransfer
    {
        return $this->productLabelStorageClient->findLabelByKey($labelKey, $localeName);
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabelsByIdProductAbstract(int $idProductAbstract, string $localeName): array
    {
        return $this->productLabelStorageClient->findLabelsByIdProductAbstract($idProductAbstract, $localeName);
    }
}
