<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabel\Storage;

use Generated\Shared\Transfer\StorageProductLabelTransfer;
use Spryker\Client\ProductLabel\Dependency\Client\ProductLabelToStorageInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;
use Spryker\Shared\ProductLabel\ProductLabelConstants;

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
     * @param int[] $idsProductLabel
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer[]
     */
    public function findSortedLabelsByIdsProductLabel(array $idsProductLabel, $localeName)
    {
        $productLabelCollection = $this->getProductLabelsFromDictionary($idsProductLabel, $localeName);
        $productLabelCollection = $this->sortCollection($productLabelCollection);
        $productLabelCollection = $this->extractExclusive($productLabelCollection);

        return $productLabelCollection;
    }

    /**
     * @param int $idProductLabel
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer|null
     */
    public function findStorageProductLabelByIdProductLabel($idProductLabel, $localeName)
    {
        $dictionary = $this->getLabelDictionary($localeName);

        if (!array_key_exists($idProductLabel, $dictionary)) {
            return null;
        }

        return $dictionary[$idProductLabel];
    }

    /**
     * @param int $labelName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer|null
     */
    public function findStorageProductLabelByName($labelName, $localeName)
    {
        $dictionary = $this->getLabelDictionaryByName($localeName);

        if (!array_key_exists($labelName, $dictionary)) {
            return null;
        }

        return $dictionary[$labelName];
    }

    /**
     * @param int[] $idsProductLabel
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer[]
     */
    protected function getProductLabelsFromDictionary(array $idsProductLabel, $localeName)
    {
        $dictionary = $this->getLabelDictionary($localeName);
        $productLabelCollection = [];

        foreach ($idsProductLabel as $idProductLabel) {
            if (!array_key_exists($idProductLabel, $dictionary)) {
                continue;
            }

            $productLabelCollection[] = $dictionary[$idProductLabel];
        }

        return $productLabelCollection;
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer[]
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
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer[]
     */
    protected function getLabelDictionaryByName($localeName)
    {
        static $labelDictionaryByName = null;

        if ($labelDictionaryByName === null) {
            $labelDictionaryByName = $this->initializeLabelDictionaryByName($localeName);
        }

        return $labelDictionaryByName;
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer[]
     */
    protected function initializeLabelDictionary($localeName)
    {
        $labelsByIds = [];

        foreach ($this->readLabelDictionary($localeName) as $productLabelData) {
            $storageProductLabelTransfer = new StorageProductLabelTransfer();
            $storageProductLabelTransfer->fromArray($productLabelData, true);

            $labelsByIds[$storageProductLabelTransfer->getIdProductLabel()] = $storageProductLabelTransfer;
        }

        return $labelsByIds;
    }

    /**
     * @param string $localeName
     *
     * @return array
     */
    protected function initializeLabelDictionaryByName($localeName)
    {
        $labelDictionaryByName = [];

        $labelDictionary = $this->getLabelDictionary($localeName);
        foreach ($labelDictionary as $storageProductLabelTransfer) {
            $labelDictionaryByName[$storageProductLabelTransfer->getName()] = $storageProductLabelTransfer;
        }

        return $labelDictionaryByName;
    }

    /**
     * @param string $localeName
     *
     * @return array
     */
    protected function readLabelDictionary($localeName)
    {
        $storageKey = $this->keyBuilder->generateKey(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY_IDENTIFIER,
            $localeName
        );

        return $this->storageClient->get($storageKey);
    }

    /**
     * @param \Generated\Shared\Transfer\StorageProductLabelTransfer[] $productLabelCollection
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer[]
     */
    protected function extractExclusive(array $productLabelCollection)
    {
        if (count($productLabelCollection) <= 1) {
            return $productLabelCollection;
        }

        foreach ($productLabelCollection as $storageProductLabelTransfer) {
            if ($storageProductLabelTransfer->getIsExclusive()) {
                return [$storageProductLabelTransfer];
            }
        }

        return $productLabelCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\StorageProductLabelTransfer[] $productLabelCollection
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer[]
     */
    protected function sortCollection(array $productLabelCollection)
    {
        if (count($productLabelCollection) <= 1) {
            return $productLabelCollection;
        }

        usort($productLabelCollection, function (
            StorageProductLabelTransfer $productLabelTransferA,
            StorageProductLabelTransfer $productLabelTransferB
        ) {
            return ($productLabelTransferA->getPosition() > $productLabelTransferB->getPosition()) ? 1 : -1;
        });

        return $productLabelCollection;
    }

}
