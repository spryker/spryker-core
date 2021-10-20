<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\RestQuoteRequestItemTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsConfiguredBundleTransfer;
use Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToGlossaryStorageClientInterface;

class QuoteRequestItemExpander implements QuoteRequestItemExpanderInterface
{
    /**
     * @var \Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(ConfigurableBundlesRestApiToGlossaryStorageClientInterface $glossaryStorageClient)
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
    public function expandRestQuoteRequestItemWithConfiguredBundle(
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
            $this->expandRestQuoteRequestsAttributesTransfer($restQuoteRequestsAttributesTransfer, $quoteRequestTransfer);
            $glossaryStorageKeys = $this->expandGlossaryStorageKeysByConfiguredBundleTemplateName($quoteRequestTransfer->getLatestVersion()->getQuote()->getItems(), $glossaryStorageKeys);
        }

        $translations = $this->glossaryStorageClient->translateBulk($glossaryStorageKeys, $localeName);

        return $this->setTranslations(
            $restQuoteRequestsAttributesTransfers,
            $translations,
        );
    }

    /**
     * @phpstan-param \ArrayObject<int,\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param array<string> $glossaryStorageKeys
     *
     * @return array<string>
     */
    protected function expandGlossaryStorageKeysByConfiguredBundleTemplateName(ArrayObject $itemTransfers, array $glossaryStorageKeys): array
    {
        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getConfiguredBundle()) {
                continue;
            }
            $templateName = $itemTransfer->getConfiguredBundle()
                ->getTemplate()
                ->getName();

            if ($templateName !== null && !in_array($templateName, $glossaryStorageKeys)) {
                $glossaryStorageKeys[] = $templateName;
            }
        }

        return $glossaryStorageKeys;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestItemTransfer> $restQuoteRequestItemsByGroupKey
     *
     * @return bool
     */
    protected function isRestQuoteRequestItemTransferExpandable(ItemTransfer $itemTransfer, array $restQuoteRequestItemsByGroupKey): bool
    {
        return isset($restQuoteRequestItemsByGroupKey[$itemTransfer->getGroupKey()])
            && $itemTransfer->getConfiguredBundle()
            && $itemTransfer->getConfiguredBundleItem();
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
            foreach ($restQuoteRequestItemTransfers as $restQuoteRequestItemTransfer) {
                 $this->translateRestQuoteRequestItemTransfer($restQuoteRequestItemTransfer, $translations);
            }
        }

        return $restQuoteRequestsAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestItemTransfer $restQuoteRequestItemTransfer
     * @param array<string> $translations
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestItemTransfer
     */
    protected function translateRestQuoteRequestItemTransfer(
        RestQuoteRequestItemTransfer $restQuoteRequestItemTransfer,
        array $translations
    ): RestQuoteRequestItemTransfer {
        if ($restQuoteRequestItemTransfer->getConfiguredBundle()) {
            $templateName = $restQuoteRequestItemTransfer->getConfiguredBundle()
                ->getTemplate()
                ->getName();

            if ($templateName && isset($translations[$templateName])) {
                $restQuoteRequestItemTransfer->getConfiguredBundle()
                    ->getTemplate()
                    ->setName($translations[$templateName]);
            }
        }

        return $restQuoteRequestItemTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestItemTransfer> $restQuoteRequestItemTransfers
     *
     * @return array<\Generated\Shared\Transfer\RestQuoteRequestItemTransfer>
     */
    protected function getRestQuoteRequestsItemTransferIndexedByGroupKey(array $restQuoteRequestItemTransfers): array
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
     * @param \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer
     */
    protected function expandRestQuoteRequestsAttributesTransfer(
        RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer,
        QuoteRequestTransfer $quoteRequestTransfer
    ): RestQuoteRequestsAttributesTransfer {
        $itemTransfers = $quoteRequestTransfer->getLatestVersion()->getQuote()->getItems();
        $restQuoteRequestItemsByGroupKey = $this->getRestQuoteRequestsItemTransferIndexedByGroupKey(($restQuoteRequestsAttributesTransfer->getShownVersion()->getCart()->getItems())->getArrayCopy());

        foreach ($itemTransfers as $itemTransfer) {
            if (
                !isset($restQuoteRequestItemsByGroupKey[$itemTransfer->getGroupKey()]) ||
                !$itemTransfer->getConfiguredBundle() ||
                !$itemTransfer->getConfiguredBundleItem()
            ) {
                continue;
            }

            $restConfiguredBundleTransfer = (new RestQuoteRequestsConfiguredBundleTransfer())
                ->fromArray($itemTransfer->getConfiguredBundle()->toArray(), true);

            $restConfiguredBundleItemTransfer = (new RestQuoteRequestsConfiguredBundleItemTransfer())
                ->fromArray($itemTransfer->getConfiguredBundleItem()->toArray(), true);

            $restQuoteRequestItemsByGroupKey[$itemTransfer->getGroupKey()]
                ->setConfiguredBundle($restConfiguredBundleTransfer)
                ->setConfiguredBundleItem($restConfiguredBundleItemTransfer);
        }

        return $restQuoteRequestsAttributesTransfer;
    }
}
