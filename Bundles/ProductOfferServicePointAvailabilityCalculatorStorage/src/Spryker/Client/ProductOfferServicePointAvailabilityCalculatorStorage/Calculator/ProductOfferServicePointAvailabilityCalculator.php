<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Calculator;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Calculator\Strategy\ProductOfferServicePointAvailabilityCalculatorStrategyInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Expander\ProductOfferServicePointAvailabilityRequestItemsExpanderInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Reader\ProductOfferServicePointAvailabilityReaderInterface;

class ProductOfferServicePointAvailabilityCalculator implements ProductOfferServicePointAvailabilityCalculatorInterface
{
    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Reader\ProductOfferServicePointAvailabilityReaderInterface
     */
    protected ProductOfferServicePointAvailabilityReaderInterface $productOfferServicePointAvailabilityReader;

    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Expander\ProductOfferServicePointAvailabilityRequestItemsExpanderInterface
     */
    protected ProductOfferServicePointAvailabilityRequestItemsExpanderInterface $productOfferServicePointAvailabilityRequestItemsExpander;

    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Calculator\Strategy\ProductOfferServicePointAvailabilityCalculatorStrategyInterface
     */
    protected ProductOfferServicePointAvailabilityCalculatorStrategyInterface $defaultProductOfferServicePointAvailabilityCalculatorStrategy;

    /**
     * @var list<\Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorageExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityCalculatorStrategyPluginInterface>
     */
    protected array $productOfferServicePointAvailabilityCalculatorStrategyPlugins;

    /**
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Reader\ProductOfferServicePointAvailabilityReaderInterface $productOfferServicePointAvailabilityReader
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Expander\ProductOfferServicePointAvailabilityRequestItemsExpanderInterface $productOfferServicePointAvailabilityRequestItemsExpander
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Calculator\Strategy\ProductOfferServicePointAvailabilityCalculatorStrategyInterface $defaultProductOfferServicePointAvailabilityCalculatorStrategy
     * @param list<\Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorageExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityCalculatorStrategyPluginInterface> $productOfferServicePointAvailabilityCalculatorStrategyPlugins
     */
    public function __construct(
        ProductOfferServicePointAvailabilityReaderInterface $productOfferServicePointAvailabilityReader,
        ProductOfferServicePointAvailabilityRequestItemsExpanderInterface $productOfferServicePointAvailabilityRequestItemsExpander,
        ProductOfferServicePointAvailabilityCalculatorStrategyInterface $defaultProductOfferServicePointAvailabilityCalculatorStrategy,
        array $productOfferServicePointAvailabilityCalculatorStrategyPlugins
    ) {
        $this->productOfferServicePointAvailabilityReader = $productOfferServicePointAvailabilityReader;
        $this->productOfferServicePointAvailabilityRequestItemsExpander = $productOfferServicePointAvailabilityRequestItemsExpander;
        $this->defaultProductOfferServicePointAvailabilityCalculatorStrategy = $defaultProductOfferServicePointAvailabilityCalculatorStrategy;
        $this->productOfferServicePointAvailabilityCalculatorStrategyPlugins = $productOfferServicePointAvailabilityCalculatorStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>
     */
    public function calculateProductOfferServicePointAvailabilities(
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
    ): array {
        $productOfferServicePointAvailabilityConditionsTransfer = $productOfferServicePointAvailabilityCriteriaTransfer->getProductOfferServicePointAvailabilityConditionsOrFail();
        $productOfferServicePointAvailabilityConditionsTransfer->requireProductOfferServicePointAvailabilityRequestItems();

        $productOfferServicePointAvailabilityConditionsTransfer->setProductOfferServicePointAvailabilityRequestItems(
            $this->productOfferServicePointAvailabilityRequestItemsExpander->expandWithIdentifier(
                $productOfferServicePointAvailabilityConditionsTransfer->getProductOfferServicePointAvailabilityRequestItems(),
            ),
        );

        $productOfferServicePointAvailabilityCollectionTransfer = $this->productOfferServicePointAvailabilityReader
            ->getProductOfferServicePointAvailabilities($productOfferServicePointAvailabilityCriteriaTransfer);

        $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid = $this->calculateProductOfferServicePointAvailabilitiesByStrategy(
            $productOfferServicePointAvailabilityCollectionTransfer,
            $productOfferServicePointAvailabilityConditionsTransfer,
        );

        if (count($productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid) < count($productOfferServicePointAvailabilityConditionsTransfer->getServicePointUuids())) {
            $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid = $this->addMissingServicePointUuidsToProductOfferServicePointAvailabilities(
                $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid,
                $productOfferServicePointAvailabilityConditionsTransfer->getServicePointUuids(),
            );
        }

        return $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>
     */
    protected function calculateProductOfferServicePointAvailabilitiesByStrategy(
        ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer,
        ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
    ): array {
        $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid = $this->executeProductOfferServicePointAvailabilityCalculatorStrategyPlugins(
            $productOfferServicePointAvailabilityCollectionTransfer,
            $productOfferServicePointAvailabilityConditionsTransfer,
        );

        if ($productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid) {
            return $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid;
        }

        return $this->defaultProductOfferServicePointAvailabilityCalculatorStrategy->calculateProductOfferServicePointAvailabilities(
            $productOfferServicePointAvailabilityCollectionTransfer,
            $productOfferServicePointAvailabilityConditionsTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>
     */
    protected function executeProductOfferServicePointAvailabilityCalculatorStrategyPlugins(
        ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer,
        ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
    ): array {
        foreach ($this->productOfferServicePointAvailabilityCalculatorStrategyPlugins as $productOfferServicePointAvailabilityCalculatorStrategyPlugin) {
            $isApplicable = $productOfferServicePointAvailabilityCalculatorStrategyPlugin->isApplicable(
                $productOfferServicePointAvailabilityCollectionTransfer,
                $productOfferServicePointAvailabilityConditionsTransfer,
            );

            if (!$isApplicable) {
                continue;
            }

            return $productOfferServicePointAvailabilityCalculatorStrategyPlugin->calculateProductOfferServicePointAvailabilities(
                $productOfferServicePointAvailabilityCollectionTransfer,
                $productOfferServicePointAvailabilityConditionsTransfer,
            );
        }

        return [];
    }

    /**
     * @param array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>> $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid
     * @param list<string> $servicePointUuids
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>
     */
    protected function addMissingServicePointUuidsToProductOfferServicePointAvailabilities(
        array $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid,
        array $servicePointUuids
    ): array {
        foreach ($servicePointUuids as $servicePointUuid) {
            if (!isset($productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid[$servicePointUuid])) {
                $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid[$servicePointUuid] = [];
            }
        }

        return $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid;
    }
}
