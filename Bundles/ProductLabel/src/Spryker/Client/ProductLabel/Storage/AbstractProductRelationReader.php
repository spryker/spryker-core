<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabel\Storage;

use Spryker\Client\ProductLabel\Dependency\Client\ProductLabelToStorageInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

class AbstractProductRelationReader implements AbstractProductRelationReaderInterface
{

    /**
     * @var \Spryker\Client\ProductLabel\Dependency\Client\ProductLabelToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductLabel\Storage\LabelDictionaryReaderInterface
     */
    protected $labelDictionaryReader;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @param \Spryker\Client\ProductLabel\Dependency\Client\ProductLabelToStorageInterface $storageClient
     * @param \Spryker\Client\ProductLabel\Storage\LabelDictionaryReaderInterface $labelDictionaryReader
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     */
    public function __construct(
        ProductLabelToStorageInterface $storageClient,
        LabelDictionaryReaderInterface $labelDictionaryReader,
        KeyBuilderInterface $keyBuilder
    ) {
        $this->storageClient = $storageClient;
        $this->labelDictionaryReader = $labelDictionaryReader;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelStorageProjectionTransfer[]
     */
    public function getLabelsForAbstractProduct($idProductAbstract, $localeName)
    {
        $productLabelIds = $this->getProductLabelIdsForAbstractProduct($idProductAbstract, $localeName);

        if (!count($productLabelIds)) {
            return [];
        }

        return $this->getSortedProductLabelsFromDictionary($productLabelIds, $localeName);
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array
     */
    protected function getProductLabelIdsForAbstractProduct($idProductAbstract, $localeName)
    {
        $storageKey = $this->keyBuilder->generateKey($idProductAbstract, $localeName);
        $storageData = $this->storageClient->get($storageKey);

        return $storageData;
    }

    /**
     * @param int[] $productLabelIds
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelStorageProjectionTransfer[]
     */
    protected function getSortedProductLabelsFromDictionary($productLabelIds, $localeName)
    {
        return $this->labelDictionaryReader->getSortedLabelsById($productLabelIds, $localeName);
    }

}
