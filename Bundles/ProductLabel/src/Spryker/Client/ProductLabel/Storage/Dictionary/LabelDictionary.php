<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabel\Storage\Dictionary;

use Generated\Shared\Transfer\StorageProductLabelTransfer;
use Spryker\Client\ProductLabel\Dependency\Client\ProductLabelToStorageInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;
use Spryker\Shared\ProductLabel\ProductLabelConstants;

class LabelDictionary implements LabelDictionaryInterface
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
     * @var \Spryker\Client\ProductLabel\Storage\Dictionary\KeyStrategyInterface
     */
    protected $dictionaryKeyStrategy;

    /**
     * @param \Spryker\Client\ProductLabel\Dependency\Client\ProductLabelToStorageInterface $storageClient
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     * @param \Spryker\Client\ProductLabel\Storage\Dictionary\KeyStrategyInterface $dictionaryKeyStrategy
     */
    public function __construct(
        ProductLabelToStorageInterface $storageClient,
        KeyBuilderInterface $keyBuilder,
        KeyStrategyInterface $dictionaryKeyStrategy
    ) {
        $this->storageClient = $storageClient;
        $this->keyBuilder = $keyBuilder;
        $this->dictionaryKeyStrategy = $dictionaryKeyStrategy;
    }

    /**
     * @param string $dictionaryKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer|null
     */
    public function findLabel($dictionaryKey, $localeName)
    {
        $dictionary = $this->getDictionary($localeName);

        if (!array_key_exists($dictionaryKey, $dictionary)) {
            return null;
        }

        return $dictionary[$dictionaryKey];
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductLabelTransfer[]
     */
    public function getDictionary($localeName)
    {
        /**
         * @var \Generated\Shared\Transfer\StorageProductLabelTransfer[] $labelDictionary
         */
        static $labelDictionary = [];

        $strategy = get_class($this->dictionaryKeyStrategy);

        if (!isset($labelDictionary[$strategy])) {
            $labelDictionary[$strategy] = $this->initializeLabelDictionary($localeName);
        }

        return $labelDictionary[$strategy];
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

            $dictionaryKey = $this->dictionaryKeyStrategy->getDictionaryKey($storageProductLabelTransfer);
            $labelsByIds[$dictionaryKey] = $storageProductLabelTransfer;
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
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY_IDENTIFIER,
            $localeName
        );

        return $this->storageClient->get($storageKey);
    }
}
