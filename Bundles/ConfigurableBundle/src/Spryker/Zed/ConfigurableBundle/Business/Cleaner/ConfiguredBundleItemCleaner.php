<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Cleaner;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface;

class ConfiguredBundleItemCleaner implements ConfiguredBundleItemCleanerInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface
     */
    protected $configurableBundleRepository;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface $configurableBundleRepository
     */
    public function __construct(ConfigurableBundleRepositoryInterface $configurableBundleRepository)
    {
        $this->configurableBundleRepository = $configurableBundleRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeInactiveConfiguredBundleItemsFromQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $filteredItemTransfers = new ArrayObject();

        $templateUuids = $this->extractConfigurableBundleTemplateUuids($quoteTransfer);
        $activeTemplateUuids = $this->configurableBundleRepository->getActiveConfigurableBundleTemplateUuids($templateUuids);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $templateUuid = $this->extractConfigurableBundleTemplateUuid($itemTransfer);

            if ($templateUuid && !in_array($templateUuid, $activeTemplateUuids, true)) {
                continue;
            }

            $filteredItemTransfers->append($itemTransfer);
        }

        return $quoteTransfer->setItems($filteredItemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    protected function extractConfigurableBundleTemplateUuids(QuoteTransfer $quoteTransfer): array
    {
        $configurableBundleTemplateUuids = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $configurableBundleTemplateUuids[] = $this->extractConfigurableBundleTemplateUuid($itemTransfer);
        }

        return array_filter($configurableBundleTemplateUuids);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string|null
     */
    protected function extractConfigurableBundleTemplateUuid(ItemTransfer $itemTransfer): ?string
    {
        if (!$itemTransfer->getConfiguredBundle() || !$itemTransfer->getConfiguredBundle()->getTemplate()) {
            return null;
        }

        return $itemTransfer->getConfiguredBundle()
            ->getTemplate()
            ->getUuid();
    }
}
