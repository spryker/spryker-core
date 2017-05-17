<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabel\Storage;

use Generated\Shared\Transfer\ProductLabelStorageProjectionTransfer;
use Spryker\Client\ProductLabel\Dependency\Client\ProductLabelToStorageInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;
use Spryker\Shared\ProductLabel\ProductLabelConfig;

class LabelDictionaryReader implements LabelDictionaryReaderInterface
{

    /**
     * @var \Spryker\Client\ProductLabel\Dependency\Client\ProductLabelToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @param \Spryker\Client\ProductLabel\Dependency\Client\ProductLabelToStorageInterface $storageClient
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     */
    public function __construct(
        ProductLabelToStorageInterface $storageClient,
        KeyBuilderInterface $keyBuilder
    ) {
        $this->storageClient = $storageClient;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param int[] $productLabelIds
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelStorageProjectionTransfer[]
     */
    public function getSortedLabelsById(array $productLabelIds, $localeName)
    {
        $productLabelCollection = $this->getProductLabelsFromDictionary($productLabelIds, $localeName);
        $productLabelCollection = $this->extractExclusive($productLabelCollection);
        $productLabelCollection = $this->sortCollection($productLabelCollection);

        return $productLabelCollection;
    }

    /**
     * @param array $productLabelIds
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelStorageProjectionTransfer[]
     */
    protected function getProductLabelsFromDictionary(array $productLabelIds, $localeName)
    {
        $dictionary = $this->getLabelDictionary($localeName);
        $productLabelCollection = [];

        foreach ($productLabelIds as $idProductLabel) {
            $productLabelCollection[] = $dictionary[$idProductLabel];
        }

        return $productLabelCollection;
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelStorageProjectionTransfer[]
     */
    protected function getLabelDictionary($localeName)
    {
        static $labelDictionary = null;

        if ($labelDictionary === null) {
            $labelDictionary = $this->initializeLabelDictionary($localeName);
        }

        return $labelDictionary;
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelStorageProjectionTransfer[]
     */
    protected function initializeLabelDictionary($localeName)
    {
        $labelsByIds = [];

        foreach ($this->readLabelDictionary($localeName) as $productLabelData) {
            $productLabelProjectionTransfer = new ProductLabelStorageProjectionTransfer();
            $productLabelProjectionTransfer->fromArray($productLabelData);

            $labelsByIds[$productLabelProjectionTransfer->getIdProductLabel()] = $productLabelProjectionTransfer;
        }

        return $labelsByIds;
    }

    /**
     * @param string $localeName
     *
     * @return array
     */
    protected function readLabelDictionary($localeName)
    {
        $storageKey = $this->keyBuilder->generateKey(
            ProductLabelConfig::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY_IDENTIFIER,
            $localeName
        );

        return $this->storageClient->get($storageKey);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelStorageProjectionTransfer[] $productLabelCollection
     *
     * @return \Generated\Shared\Transfer\ProductLabelStorageProjectionTransfer[]
     */
    protected function extractExclusive(array $productLabelCollection)
    {
        if (count($productLabelCollection) <= 1) {
            return $productLabelCollection;
        }

        foreach ($productLabelCollection as $productLabelProjectionTransfer) {
            if ($productLabelProjectionTransfer->getIsExclusive()) {
                return [$productLabelProjectionTransfer];
            }
        }

        return $productLabelCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelStorageProjectionTransfer[] $productLabelCollection
     *
     * @return \Generated\Shared\Transfer\ProductLabelStorageProjectionTransfer[]
     */
    protected function sortCollection(array $productLabelCollection)
    {
        if (count($productLabelCollection) <= 1) {
            return $productLabelCollection;
        }

        usort($productLabelCollection, function (
            ProductLabelStorageProjectionTransfer $productLabelTransferA,
            ProductLabelStorageProjectionTransfer $productLabelTransferB
        ) {

            return ($productLabelTransferA->getPosition() > $productLabelTransferB->getPosition()) ? 1 : -1;
        });

        return $productLabelCollection;
    }

}
