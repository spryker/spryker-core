<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface;

class InactiveConfiguredBundleItemFilter implements InactiveConfiguredBundleItemFilterInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface
     */
    protected $configurableBundleRepository;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface $configurableBundleRepository
     */
    public function __construct(
        ConfigurableBundleRepositoryInterface $configurableBundleRepository
    ) {
        $this->configurableBundleRepository = $configurableBundleRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterInactiveItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $activeConfigurableBundleTemplateUuids = $this->configurableBundleRepository->getActiveConfigurableBundleTemplateUuids(
            $this->extractConfigurableBundleItemTemplateUuids($quoteTransfer)
        );

        $filteredItemTransfers = new ArrayObject();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $configurableBundleTemplateUuid = $this->extractConfigurableBundleItemTemplateUuid($itemTransfer);

            if (!$configurableBundleTemplateUuid) {
                $filteredItemTransfers[] = $itemTransfer;

                continue;
            }

            if (!in_array($configurableBundleTemplateUuid, $activeConfigurableBundleTemplateUuids)) {
                continue;
            }

            $filteredItemTransfers[] = $itemTransfer;
        }

        return $quoteTransfer->setItems($filteredItemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    protected function extractConfigurableBundleItemTemplateUuids(QuoteTransfer $quoteTransfer): array
    {
        $configurableBundleTemplateUuids = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $configurableBundleTemplateUuids[] = $this->extractConfigurableBundleItemTemplateUuid($itemTransfer);
        }

        return array_filter($configurableBundleTemplateUuids);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string|null
     */
    public function extractConfigurableBundleItemTemplateUuid(ItemTransfer $itemTransfer): ?string
    {
        if (!$itemTransfer->getConfiguredBundle()) {
            return null;
        }

        return $itemTransfer->getConfiguredBundle()
            ->getTemplate()
            ->getUuid();
    }
}
