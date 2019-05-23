<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage\Storage;

use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;
use Spryker\Client\ProductLabelStorage\Storage\Dictionary\DictionaryFactory;

class LabelDictionaryReader implements LabelDictionaryReaderInterface
{
    /**
     * @var \Spryker\Client\ProductLabelStorage\Storage\Dictionary\DictionaryFactory
     */
    protected $dictionaryFactory;

    /**
     * @param \Spryker\Client\ProductLabelStorage\Storage\Dictionary\DictionaryFactory $dictionaryFactory
     */
    public function __construct(DictionaryFactory $dictionaryFactory)
    {
        $this->dictionaryFactory = $dictionaryFactory;
    }

    /**
     * @param int[] $idsProductLabel
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
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
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
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
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
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
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
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
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
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
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[] $productLabelDictionaryItemTransfers
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    protected function extractExclusive(array $productLabelDictionaryItemTransfers)
    {
        if (count($productLabelDictionaryItemTransfers) <= 1) {
            return $productLabelDictionaryItemTransfers;
        }

        foreach ($productLabelDictionaryItemTransfers as $productLabelDictionaryItemTransfer) {
            if ($productLabelDictionaryItemTransfer->getIsExclusive()) {
                return [$productLabelDictionaryItemTransfer];
            }
        }

        return $productLabelDictionaryItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[] $productLabelDictionaryItemTransfers
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    protected function sortCollection(array $productLabelDictionaryItemTransfers)
    {
        if (count($productLabelDictionaryItemTransfers) <= 1) {
            return $productLabelDictionaryItemTransfers;
        }

        usort($productLabelDictionaryItemTransfers, function (
            ProductLabelDictionaryItemTransfer $productLabelTransferA,
            ProductLabelDictionaryItemTransfer $productLabelTransferB
        ) {
            return ($productLabelTransferA->getPosition() > $productLabelTransferB->getPosition()) ? 1 : -1;
        });

        return $productLabelDictionaryItemTransfers;
    }
}
