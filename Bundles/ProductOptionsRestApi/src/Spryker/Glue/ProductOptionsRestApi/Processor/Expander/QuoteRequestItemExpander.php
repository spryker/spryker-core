<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\RestQuoteRequestItemTransfer;
use Generated\Shared\Transfer\RestQuoteRequestProductOptionTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToGlossaryStorageClientInterface;

class QuoteRequestItemExpander implements QuoteRequestItemExpanderInterface
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
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer> $restQuoteRequestsAttributesTransfers
     * @param array<\Generated\Shared\Transfer\QuoteRequestTransfer> $quoteRequestTransfers
     * @param string $localeName
     *
     * @return array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer>
     */
    public function expandRestQuoteRequestItemWithProductOptions(
        array $restQuoteRequestsAttributesTransfers,
        array $quoteRequestTransfers,
        string $localeName
    ): array {
        $glossaryStorageKeys = [];
        $indexedRestQuoteRequestsAttributesTransfers = $this->getRestQuoteRequestsAttributesTransfersIndexedByQuoteRequestReference($restQuoteRequestsAttributesTransfers);
        foreach ($quoteRequestTransfers as $quoteRequestTransfer) {
            if (!isset($indexedRestQuoteRequestsAttributesTransfers[$quoteRequestTransfer->getQuoteRequestReference()])) {
                continue;
            }

            $restQuoteRequestsAttributesTransfer = $indexedRestQuoteRequestsAttributesTransfers[$quoteRequestTransfer->getQuoteRequestReference()];
            if (!$this->areTransfersValid($quoteRequestTransfer, $restQuoteRequestsAttributesTransfer)) {
                continue;
            }

            $itemTransfers = $quoteRequestTransfer->getLatestVersion()->getQuote()->getItems();
            $restQuoteRequestItemsByGroupKey = $this->getRestQuoteRequestItemsIndexedByGroupKey(($restQuoteRequestsAttributesTransfer->getShownVersion()->getCart()->getItems())->getArrayCopy());

            foreach ($itemTransfers as $itemTransfer) {
                $itemGroupKey = $itemTransfer->getGroupKey();
                if (!isset($restQuoteRequestItemsByGroupKey[$itemGroupKey])) {
                    continue;
                }
                $restQuoteRequestItemsByGroupKey[$itemGroupKey] = $this->addSelectedProductOptions($restQuoteRequestItemsByGroupKey[$itemGroupKey], $itemTransfer);
            }
            $glossaryStorageKeys = $this->expandGlossaryStorageKeysByProductOptionKeys($itemTransfers, $glossaryStorageKeys);
        }

        $translations = $this->glossaryStorageClient->translateBulk($glossaryStorageKeys, $localeName);

        return $this->setTranslations(
            $restQuoteRequestsAttributesTransfers,
            $translations,
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer> $restQuoteRequestsAttributesTransfers
     * @param array<string> $translations
     *
     * @return array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer>
     */
    protected function setTranslations(
        array $restQuoteRequestsAttributesTransfers,
        array $translations
    ): array {
        foreach ($restQuoteRequestsAttributesTransfers as $restQuoteRequestsAttributesTransfer) {
            if (!$this->isRestQuoteRequestsAttributesTransferValid($restQuoteRequestsAttributesTransfer)) {
                continue;
            }

            $restQuoteRequestItemTransfers = $restQuoteRequestsAttributesTransfer->getShownVersion()->getCart()->getItems();

            /** @var \Generated\Shared\Transfer\RestQuoteRequestItemTransfer $restQuoteRequestItemTransfer */
            foreach ($restQuoteRequestItemTransfers as $restQuoteRequestItemTransfer) {
                foreach ($restQuoteRequestItemTransfer->getSelectedProductOptions() as $productOption) {
                    if (isset($translations[$productOption->getOptionName()])) {
                        $productOption->setOptionName($translations[$productOption->getOptionName()]);
                    }
                    if (isset($translations[$productOption->getOptionGroupName()])) {
                        $productOption->setOptionGroupName($translations[$productOption->getOptionGroupName()]);
                    }
                }
            }
        }

        return $restQuoteRequestsAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer
     *
     * @return bool
     */
    protected function areTransfersValid(
        QuoteRequestTransfer $quoteRequestTransfer,
        RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer
    ): bool {
        return $quoteRequestTransfer->getLatestVersion() !== null
            && $quoteRequestTransfer->getLatestVersion()->getQuote() !== null
            && $this->isRestQuoteRequestsAttributesTransferValid($restQuoteRequestsAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer
     *
     * @return bool
     */
    protected function isRestQuoteRequestsAttributesTransferValid(RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer): bool
    {
        return $restQuoteRequestsAttributesTransfer->getShownVersion() !== null
            && $restQuoteRequestsAttributesTransfer->getShownVersion()->getCart() !== null;
    }

    /**
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestItemTransfer> $restQuoteRequestItemTransfers
     *
     * @return array<\Generated\Shared\Transfer\RestQuoteRequestItemTransfer>
     */
    protected function getRestQuoteRequestItemsIndexedByGroupKey(array $restQuoteRequestItemTransfers): array
    {
        $restQuoteRequestItemTransfersByGroupKey = [];
        foreach ($restQuoteRequestItemTransfers as $restQuoteRequestItemTransfer) {
            $restQuoteRequestItemTransfersByGroupKey[$restQuoteRequestItemTransfer->getGroupKey()] = $restQuoteRequestItemTransfer;
        }

        return $restQuoteRequestItemTransfersByGroupKey;
    }

    /**
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer> $restQuoteRequestsAttributesTransfers
     *
     * @return array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer>
     */
    protected function getRestQuoteRequestsAttributesTransfersIndexedByQuoteRequestReference(array $restQuoteRequestsAttributesTransfers): array
    {
        $indexedRestQuoteRequestsAttributesTransfers = [];
        foreach ($restQuoteRequestsAttributesTransfers as $restQuoteRequestsAttributesTransfer) {
            $indexedRestQuoteRequestsAttributesTransfers[$restQuoteRequestsAttributesTransfer->getQuoteRequestReference()] = $restQuoteRequestsAttributesTransfer;
        }

        return $indexedRestQuoteRequestsAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestItemTransfer $restQuoteRequestItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestItemTransfer
     */
    protected function addSelectedProductOptions(
        RestQuoteRequestItemTransfer $restQuoteRequestItemTransfer,
        ItemTransfer $itemTransfer
    ): RestQuoteRequestItemTransfer {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $restQuoteRequestProductOptionTransfer =
                (new RestQuoteRequestProductOptionTransfer())
                    ->fromArray($productOptionTransfer->toArray(), true)
                    ->setOptionGroupName($productOptionTransfer->getGroupName())
                    ->setOptionName($productOptionTransfer->getValue())
                    ->setPrice($productOptionTransfer->getSumPrice());

            $restQuoteRequestItemTransfer->addSelectedProductOption($restQuoteRequestProductOptionTransfer);
        }

        return $restQuoteRequestItemTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param array<string> $glossaryStorageKeys
     *
     * @return array<string>
     */
    protected function expandGlossaryStorageKeysByProductOptionKeys(ArrayObject $itemTransfers, array $glossaryStorageKeys): array
    {
        foreach ($itemTransfers as $itemTransfer) {
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                if (!in_array($productOptionTransfer->getGroupName(), $glossaryStorageKeys)) {
                    $glossaryStorageKeys[] = $productOptionTransfer->getGroupName();
                }
                if (!in_array($productOptionTransfer->getValue(), $glossaryStorageKeys)) {
                    $glossaryStorageKeys[] = $productOptionTransfer->getValue();
                }
            }
        }

        return $glossaryStorageKeys;
    }
}
