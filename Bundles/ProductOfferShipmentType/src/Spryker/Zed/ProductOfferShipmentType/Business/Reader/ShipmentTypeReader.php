<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Spryker\Zed\ProductOfferShipmentType\Business\Expander\ShipmentTypeProductOfferShipmentTypeCollectionExpanderInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Filter\ShipmentTypeProductOfferShipmentTypeCollectionFilterInterface;
use Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToShipmentTypeFacadeInterface;

class ShipmentTypeReader implements ShipmentTypeReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Filter\ShipmentTypeProductOfferShipmentTypeCollectionFilterInterface
     */
    protected ShipmentTypeProductOfferShipmentTypeCollectionFilterInterface $shipmentTypeProductOfferShipmentTypeCollectionFilter;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Expander\ShipmentTypeProductOfferShipmentTypeCollectionExpanderInterface
     */
    protected ShipmentTypeProductOfferShipmentTypeCollectionExpanderInterface $shipmentTypeProductOfferShipmentTypeCollectionExpander;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface
     */
    protected ShipmentTypeExtractorInterface $shipmentTypeExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToShipmentTypeFacadeInterface
     */
    protected ProductOfferShipmentTypeToShipmentTypeFacadeInterface $shipmentTypeFacade;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Filter\ShipmentTypeProductOfferShipmentTypeCollectionFilterInterface $shipmentTypeProductOfferShipmentTypeCollectionFilter
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Expander\ShipmentTypeProductOfferShipmentTypeCollectionExpanderInterface $shipmentTypeProductOfferShipmentTypeCollectionExpander
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface $shipmentTypeExtractor
     * @param \Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToShipmentTypeFacadeInterface $shipmentTypeFacade
     */
    public function __construct(
        ShipmentTypeProductOfferShipmentTypeCollectionFilterInterface $shipmentTypeProductOfferShipmentTypeCollectionFilter,
        ShipmentTypeProductOfferShipmentTypeCollectionExpanderInterface $shipmentTypeProductOfferShipmentTypeCollectionExpander,
        ShipmentTypeExtractorInterface $shipmentTypeExtractor,
        ProductOfferShipmentTypeToShipmentTypeFacadeInterface $shipmentTypeFacade
    ) {
        $this->shipmentTypeProductOfferShipmentTypeCollectionFilter = $shipmentTypeProductOfferShipmentTypeCollectionFilter;
        $this->shipmentTypeProductOfferShipmentTypeCollectionExpander = $shipmentTypeProductOfferShipmentTypeCollectionExpander;
        $this->shipmentTypeExtractor = $shipmentTypeExtractor;
        $this->shipmentTypeFacade = $shipmentTypeFacade;
    }

    /**
     * @param list<string> $shipmentTypeUuids
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    public function getShipmentTypeCollectionByShipmentTypeUuids(array $shipmentTypeUuids): ShipmentTypeCollectionTransfer
    {
        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())->setUuids($shipmentTypeUuids);
        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())
            ->setShipmentTypeConditions($shipmentTypeConditionsTransfer);

        return $this->shipmentTypeFacade->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);
    }

    /**
     * @param list<int> $shipmentTypeIds
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    public function getShipmentTypeCollectionByShipmentTypeIds(array $shipmentTypeIds): ShipmentTypeCollectionTransfer
    {
        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())->setShipmentTypeIds($shipmentTypeIds);
        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())
            ->setShipmentTypeConditions($shipmentTypeConditionsTransfer);

        return $this->shipmentTypeFacade->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorCriteriaTransfer $productOfferShipmentTypeIteratorCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function getShipmentTypesForProductOfferShipmentTypeCollection(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer,
        ProductOfferShipmentTypeIteratorCriteriaTransfer $productOfferShipmentTypeIteratorCriteriaTransfer
    ): ProductOfferShipmentTypeCollectionTransfer {
        $shipmentTypeCollectionTransfer = $this->getShipmentTypeCollectionTransfer(
            $productOfferShipmentTypeIteratorCriteriaTransfer->getProductOfferShipmentTypeIteratorConditionsOrFail(),
            $this->extractShipmentTypeIdsFromProductOfferShipmentTypeTransfers(
                $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes(),
            ),
        );
        $productOfferShipmentTypeCollectionTransfer = $this->shipmentTypeProductOfferShipmentTypeCollectionFilter
            ->filterProductOfferShipmentTypeCollectionTransfersByShipmentTypeCollectionTransfer(
                $productOfferShipmentTypeCollectionTransfer,
                $shipmentTypeCollectionTransfer,
            );

        return $this->shipmentTypeProductOfferShipmentTypeCollectionExpander
            ->expandProductOfferShipmentTypeCollectionWithShipmentTypes(
                $productOfferShipmentTypeCollectionTransfer,
                $shipmentTypeCollectionTransfer,
            );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer> $productOfferShipmentTypeTransfers
     *
     * @return list<int>
     */
    protected function extractShipmentTypeIdsFromProductOfferShipmentTypeTransfers(ArrayObject $productOfferShipmentTypeTransfers): array
    {
        $shipmentTypeIds = [];
        foreach ($productOfferShipmentTypeTransfers as $productOfferShipmentTypeTransfer) {
            $shipmentTypeIds[] = $this->shipmentTypeExtractor->extractShipmentTypeIdsFromShipmentTypeTransfers(
                $productOfferShipmentTypeTransfer->getShipmentTypes(),
            );
        }

        return array_merge(...$shipmentTypeIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorConditionsTransfer $productOfferShipmentTypeIteratorConditionsTransfer
     * @param list<int> $shipmentTypeIds
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    protected function getShipmentTypeCollectionTransfer(
        ProductOfferShipmentTypeIteratorConditionsTransfer $productOfferShipmentTypeIteratorConditionsTransfer,
        array $shipmentTypeIds
    ): ShipmentTypeCollectionTransfer {
        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())
            ->setIsActive($productOfferShipmentTypeIteratorConditionsTransfer->getIsActiveShipmentType())
            ->setWithStoreRelations(true)
            ->setShipmentTypeIds($shipmentTypeIds);
        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())->setShipmentTypeConditions($shipmentTypeConditionsTransfer);

        return $this->shipmentTypeFacade->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);
    }
}
