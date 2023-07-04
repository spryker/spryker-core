<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Expander;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToShipmentTypeFacadeInterface;
use Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface;

class ProductOfferExpander implements ProductOfferExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface
     */
    protected ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToShipmentTypeFacadeInterface
     */
    protected ProductOfferShipmentTypeToShipmentTypeFacadeInterface $shipmentTypeFacade;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository
     * @param \Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToShipmentTypeFacadeInterface $shipmentTypeFacade
     */
    public function __construct(
        ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository,
        ProductOfferShipmentTypeToShipmentTypeFacadeInterface $shipmentTypeFacade
    ) {
        $this->productOfferShipmentTypeRepository = $productOfferShipmentTypeRepository;
        $this->shipmentTypeFacade = $shipmentTypeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function expandProductOfferWithShipmentTypes(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $shipmentTypeIds = $this->productOfferShipmentTypeRepository->getShipmentTypeIdsByIdProductOffer(
            $productOfferTransfer->getIdProductOfferOrFail(),
        );
        if (!$shipmentTypeIds) {
            return $productOfferTransfer;
        }

        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())
            ->setShipmentTypeIds($shipmentTypeIds);
        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())
            ->setShipmentTypeConditions($shipmentTypeConditionsTransfer);

        $shipmentTypeCollectionTransfer = $this->shipmentTypeFacade->getShipmentTypeCollection(
            $shipmentTypeCriteriaTransfer,
        );

        return $productOfferTransfer->setShipmentTypes($shipmentTypeCollectionTransfer->getShipmentTypes());
    }
}
