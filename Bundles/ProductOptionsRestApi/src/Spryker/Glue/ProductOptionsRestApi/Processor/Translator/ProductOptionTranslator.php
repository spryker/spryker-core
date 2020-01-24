<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Translator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToGlossaryStorageClientInterface;

class ProductOptionTranslator implements ProductOptionTranslatorInterface
{
    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(ProductOptionsRestApiToGlossaryStorageClientInterface $glossaryStorageClient)
    {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[] $productAbstractOptionStorageTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[]
     */
    public function translateProductAbstractOptionStorageTransfers(
        array $productAbstractOptionStorageTransfers,
        string $localeName
    ): array {
        $glossaryStorageKeys = $this->getGlossaryStorageKeysFromProductAbstractOptionStorageTransfers(
            $productAbstractOptionStorageTransfers
        );
        $translations = $this->glossaryStorageClient->translateBulk($glossaryStorageKeys, $localeName);

        foreach ($productAbstractOptionStorageTransfers as $productAbstractOptionStorageTransfer) {
            $this->setTranslationsToProductAbstractOptionStorageTransfer(
                $productAbstractOptionStorageTransfer,
                $translations
            );
        }

        return $productAbstractOptionStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function translateItemTransfer(ItemTransfer $itemTransfer, string $localeName): ItemTransfer
    {
        $glossaryStorageKeys = $this->getGlossaryStorageKeysFromItemTransfer($itemTransfer);
        $translations = $this->glossaryStorageClient->translateBulk($glossaryStorageKeys, $localeName);

        return $this->setTranslationsToItemTransfer($itemTransfer, $translations);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string[] $translations
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function setTranslationsToItemTransfer(ItemTransfer $itemTransfer, array $translations): ItemTransfer
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionTransfer->setGroupName($translations[$productOptionTransfer->getGroupName()]);
            $productOptionTransfer->setValue($translations[$productOptionTransfer->getValue()]);
        }

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
     * @param string[] $translations
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    protected function setTranslationsToProductAbstractOptionStorageTransfer(
        ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer,
        array $translations
    ): ProductAbstractOptionStorageTransfer {
        foreach ($productAbstractOptionStorageTransfer->getProductOptionGroups() as $productOptionGroupStorageTransfer) {
            $productOptionGroupStorageTransfer->setName($translations[$productOptionGroupStorageTransfer->getName()]);

            foreach ($productOptionGroupStorageTransfer->getProductOptionValues() as $productOptionValueStorageTransfer) {
                $productOptionValueStorageTransfer->setValue($translations[$productOptionValueStorageTransfer->getValue()]);
            }
        }

        return $productAbstractOptionStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[] $productAbstractOptionStorageTransfers
     *
     * @return string[]
     */
    protected function getGlossaryStorageKeysFromProductAbstractOptionStorageTransfers(array $productAbstractOptionStorageTransfers): array
    {
        $glossaryStorageKeys = [];
        $productOptionGroupStorageTransfers = [];
        foreach ($productAbstractOptionStorageTransfers as $productAbstractOptionStorageTransfer) {
            $productOptionGroupStorageTransfers[] = $productAbstractOptionStorageTransfer->getProductOptionGroups()
                ->getArrayCopy();
        }
        $productOptionGroupStorageTransfers = array_merge(...$productOptionGroupStorageTransfers);

        $productOptionValueStorageTransfers = [];
        foreach ($productOptionGroupStorageTransfers as $productOptionGroupStorageTransfer) {
            $glossaryStorageKeys[] = $productOptionGroupStorageTransfer->getName();
            $productOptionValueStorageTransfers[] = $productOptionGroupStorageTransfer->getProductOptionValues()
                ->getArrayCopy();
        }
        $productOptionValueStorageTransfers = array_merge(...$productOptionValueStorageTransfers);

        foreach ($productOptionValueStorageTransfers as $productOptionValueStorageTransfer) {
            $glossaryStorageKeys[] = $productOptionValueStorageTransfer->getValue();
        }

        return array_unique($glossaryStorageKeys);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string[]
     */
    protected function getGlossaryStorageKeysFromItemTransfer(ItemTransfer $itemTransfer): array
    {
        $glossaryStorageKeys = [];
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $glossaryStorageKeys[] = $productOptionTransfer->getGroupName();
            $glossaryStorageKeys[] = $productOptionTransfer->getValue();
        }

        return $glossaryStorageKeys;
    }
}
