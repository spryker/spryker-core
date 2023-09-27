<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointsRestApi\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ServicePointConditionsTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Spryker\Zed\ServicePointsRestApi\Dependency\Facade\ServicePointsRestApiToServicePointFacadeInterface;

class QuoteItemMapper implements QuoteItemMapperInterface
{
    /**
     * @var \Spryker\Zed\ServicePointsRestApi\Dependency\Facade\ServicePointsRestApiToServicePointFacadeInterface
     */
    protected ServicePointsRestApiToServicePointFacadeInterface $servicePointFacade;

    /**
     * @param \Spryker\Zed\ServicePointsRestApi\Dependency\Facade\ServicePointsRestApiToServicePointFacadeInterface $servicePointFacade
     */
    public function __construct(ServicePointsRestApiToServicePointFacadeInterface $servicePointFacade)
    {
        $this->servicePointFacade = $servicePointFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapServicePointToQuoteItem(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        if (!$restCheckoutRequestAttributesTransfer->getServicePoints()->count()) {
            return $quoteTransfer;
        }
        $restServicePointTransfers = $restCheckoutRequestAttributesTransfer->getServicePoints();
        $servicePointTransfers = $this->getServicePoints($restServicePointTransfers);

        $servicePointTransfersIndexedByItemGroupKey = $this->getServicePointTransfersIndexedByItemGroupKey(
            $servicePointTransfers,
            $restServicePointTransfers,
        );

        return $this->mapServicePointTransfersToQuoteItemTransfers(
            $servicePointTransfersIndexedByItemGroupKey,
            $quoteTransfer,
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\RestServicePointTransfer> $restServicePointTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer>
     */
    protected function getServicePoints(ArrayObject $restServicePointTransfers): ArrayObject
    {
        $servicePointIds = $this->extractServicePointIdsFromRestServicePointTransfers($restServicePointTransfers);
        $servicePointCriteriaTransfer = $this->createServicePointCriteriaTransfer($servicePointIds);

        $servicePointCollectionTransfer = $this->servicePointFacade->getServicePointCollection($servicePointCriteriaTransfer);

        return $servicePointCollectionTransfer->getServicePoints();
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfersIndexedByItemGroupKey
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapServicePointTransfersToQuoteItemTransfers(
        array $servicePointTransfersIndexedByItemGroupKey,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (isset($servicePointTransfersIndexedByItemGroupKey[$itemTransfer->getGroupKey()])) {
                $itemTransfer->setServicePoint($servicePointTransfersIndexedByItemGroupKey[$itemTransfer->getGroupKey()]);
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param list<string> $servicePointIds
     *
     * @return \Generated\Shared\Transfer\ServicePointCriteriaTransfer
     */
    protected function createServicePointCriteriaTransfer(
        array $servicePointIds
    ): ServicePointCriteriaTransfer {
        return (new ServicePointCriteriaTransfer())
            ->setServicePointConditions(
                (new ServicePointConditionsTransfer())
                    ->setUuids($servicePointIds)
                    ->setWithStoreRelations(true),
            );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\RestServicePointTransfer> $restServicePointTransfers
     *
     * @return list<string>
     */
    protected function extractServicePointIdsFromRestServicePointTransfers(ArrayObject $restServicePointTransfers): array
    {
        $servicePointIds = [];

        foreach ($restServicePointTransfers as $restServicePointTransfer) {
            $servicePointIds[] = $restServicePointTransfer->getIdServicePointOrFail();
        }

        return $servicePointIds;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\RestServicePointTransfer> $restServicePointTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ServicePointTransfer>
     */
    protected function getServicePointTransfersIndexedByItemGroupKey(
        ArrayObject $servicePointTransfers,
        ArrayObject $restServicePointTransfers
    ): array {
        $servicePointTransfersIndexedByItemGroupKey = [];
        $servicePointsTransfersIndexedByUuid = $this->getServicePointTransfersIndexedByUuid($servicePointTransfers);

        foreach ($restServicePointTransfers as $restServicePointTransfer) {
            foreach ($restServicePointTransfer->getItems() as $itemsGroupKey) {
                if (isset($servicePointsTransfersIndexedByUuid[$restServicePointTransfer->getIdServicePointOrFail()])) {
                    $servicePointTransfersIndexedByItemGroupKey[$itemsGroupKey] = $servicePointsTransfersIndexedByUuid[$restServicePointTransfer->getIdServicePointOrFail()];
                }
            }
        }

        return $servicePointTransfersIndexedByItemGroupKey;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ServicePointTransfer>
     */
    protected function getServicePointTransfersIndexedByUuid(ArrayObject $servicePointTransfers): array
    {
        $servicePointTransfersIndexedByUuid = [];

        foreach ($servicePointTransfers as $servicePointTransfer) {
            $servicePointTransfersIndexedByUuid[$servicePointTransfer->getUuid()] = $servicePointTransfer;
        }

        return $servicePointTransfersIndexedByUuid;
    }
}
