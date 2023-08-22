<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesRestApi\Processor\Reader;

use Generated\Shared\Transfer\ShipmentTypeStorageConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShipmentTypesRestApi\Dependency\Client\ShipmentTypesRestApiToShipmentTypeStorageClientInterface;
use Spryker\Glue\ShipmentTypesRestApi\Dependency\Client\ShipmentTypesRestApiToStoreClientInterface;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Builder\ShipmentTypeResponseBuilderInterface;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Sorter\ShipmentTypeSorterInterface;

class ShipmentTypeReader implements ShipmentTypeReaderInterface
{
    /**
     * @var \Spryker\Glue\ShipmentTypesRestApi\Dependency\Client\ShipmentTypesRestApiToShipmentTypeStorageClientInterface
     */
    protected ShipmentTypesRestApiToShipmentTypeStorageClientInterface $shipmentTypeStorageClient;

    /**
     * @var \Spryker\Glue\ShipmentTypesRestApi\Dependency\Client\ShipmentTypesRestApiToStoreClientInterface
     */
    protected ShipmentTypesRestApiToStoreClientInterface $storeClient;

    /**
     * @var \Spryker\Glue\ShipmentTypesRestApi\Processor\Builder\ShipmentTypeResponseBuilderInterface
     */
    protected ShipmentTypeResponseBuilderInterface $shipmentTypeResponseBuilder;

    /**
     * @var \Spryker\Glue\ShipmentTypesRestApi\Processor\Sorter\ShipmentTypeSorterInterface
     */
    protected ShipmentTypeSorterInterface $shipmentTypeSorter;

    /**
     * @param \Spryker\Glue\ShipmentTypesRestApi\Dependency\Client\ShipmentTypesRestApiToShipmentTypeStorageClientInterface $shipmentTypeStorageClient
     * @param \Spryker\Glue\ShipmentTypesRestApi\Dependency\Client\ShipmentTypesRestApiToStoreClientInterface $storeClient
     * @param \Spryker\Glue\ShipmentTypesRestApi\Processor\Builder\ShipmentTypeResponseBuilderInterface $shipmentTypeResponseBuilder
     * @param \Spryker\Glue\ShipmentTypesRestApi\Processor\Sorter\ShipmentTypeSorterInterface $shipmentTypeSorter
     */
    public function __construct(
        ShipmentTypesRestApiToShipmentTypeStorageClientInterface $shipmentTypeStorageClient,
        ShipmentTypesRestApiToStoreClientInterface $storeClient,
        ShipmentTypeResponseBuilderInterface $shipmentTypeResponseBuilder,
        ShipmentTypeSorterInterface $shipmentTypeSorter
    ) {
        $this->shipmentTypeStorageClient = $shipmentTypeStorageClient;
        $this->storeClient = $storeClient;
        $this->shipmentTypeResponseBuilder = $shipmentTypeResponseBuilder;
        $this->shipmentTypeSorter = $shipmentTypeSorter;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getShipmentType(RestRequestInterface $restRequest): RestResponseInterface
    {
        $shipmentTypeStorageCollectionTransfer = $this->shipmentTypeStorageClient->getShipmentTypeStorageCollection(
            $this->createShipmentTypeStorageCriteriaTransfer($restRequest),
        );
        if ($shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages()->count() === 0) {
            return $this
                ->shipmentTypeResponseBuilder
                ->createShipmentTypeNotFoundErrorResponse($restRequest->getMetadata()->getLocale());
        }
        /** @var \Generated\Shared\Transfer\ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer */
        $shipmentTypeStorageTransfer = $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages()->offsetGet(0);

        return $this->shipmentTypeResponseBuilder->createShipmentTypeRestResponse($shipmentTypeStorageTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getShipmentTypeCollection(RestRequestInterface $restRequest): RestResponseInterface
    {
        $shipmentTypeStorageCollectionTransfer = $this->shipmentTypeStorageClient->getShipmentTypeStorageCollection(
            $this->createShipmentTypeStorageCriteriaTransfer($restRequest),
        );
        $shipmentTypeStorageCollectionTransfer = $this->shipmentTypeSorter->sortShipmentTypeStorageCollection(
            $shipmentTypeStorageCollectionTransfer,
            $restRequest->getSort(),
        );

        return $this
            ->shipmentTypeResponseBuilder
            ->createShipmentTypeCollectionRestResponse($shipmentTypeStorageCollectionTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer
     */
    protected function createShipmentTypeStorageCriteriaTransfer(RestRequestInterface $restRequest): ShipmentTypeStorageCriteriaTransfer
    {
        $shipmentTypeStorageConditionsTransfer = (new ShipmentTypeStorageConditionsTransfer())
            ->setStoreName($this->storeClient->getCurrentStore()->getNameOrFail());
        if ($restRequest->getResource()->getId()) {
            $shipmentTypeStorageConditionsTransfer->addUuid($restRequest->getResource()->getId());
        }

        return (new ShipmentTypeStorageCriteriaTransfer())
            ->setShipmentTypeStorageConditions($shipmentTypeStorageConditionsTransfer);
    }
}
