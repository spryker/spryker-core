<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabel\Storage;

use Generated\Shared\Transfer\StorageProductLabelTransfer;
use Spryker\Client\ProductLabel\Storage\Dictionary\DictionaryFactory;

class LabelDictionaryReader implements LabelDictionaryReaderInterface
{
    /**
     * @var \Spryker\Client\ProductLabel\Storage\Dictionary\DictionaryFactory
     */
    protected $dictionaryFactory;

    /**
     * @param \Spryker\Client\ProductLabel\Storage\Dictionary\DictionaryFactory $dictionaryFactory
     */
    public function __construct(DictionaryFactory $dictionaryFactory)
    {
        $this->dictionaryFactory = $dictionaryFactory;
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
    public function findLabelByIdProductLabel($idProductLabel, $localeName)
    {
        return $this->dictionaryFactory
            ->createDictionaryByIdProductLabel()
            ->findLabel($idProductLabel, $localeName);
    }

    /**
     * @param string $labelName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer|null
     */
    public function findLabelByLocalizedName($labelName, $localeName)
    {
        return $this->dictionaryFactory
            ->createDictionaryByLocalizedName()
            ->findLabel($labelName, $localeName);
    }

    /**
     * @param string $labelName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer|null
     */
    public function findLabelByName($labelName, $localeName)
    {
        return $this->dictionaryFactory
            ->createDictionaryByName()
            ->findLabel($labelName, $localeName);
    }

    /**
     * @param int[] $idsProductLabel
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer[]
     */
    protected function getProductLabelsFromDictionary(array $idsProductLabel, $localeName)
    {
        $dictionary = $this->dictionaryFactory
            ->createDictionaryByIdProductLabel()
            ->getDictionary($localeName);

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
