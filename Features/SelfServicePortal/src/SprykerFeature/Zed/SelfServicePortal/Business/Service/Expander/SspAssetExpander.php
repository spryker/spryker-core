<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander;

use ArrayObject;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Generated\Shared\Transfer\SspServiceConditionsTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ServiceReaderInterface;

class SspAssetExpander implements SspAssetExpanderInterface
{
    /**
     * @var int
     */
    protected const SERVICE_PAGINATION_PAGE = 1;

    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ServiceReaderInterface $serviceReader
     */
    public function __construct(protected ServiceReaderInterface $serviceReader)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionTransfer $sspAssetCollectionTransfer
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function expandSspAssetWithServices(
        SspAssetCollectionTransfer $sspAssetCollectionTransfer,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
    ): SspAssetCollectionTransfer {
        if (!$sspAssetCriteriaTransfer->getInclude() || !$sspAssetCriteriaTransfer->getInclude()->getWithServicesCount()) {
            return $sspAssetCollectionTransfer;
        }

        $sspServiceCollectionTransfer = $this->serviceReader->getServiceCollection(
            (new SspServiceCriteriaTransfer())
                ->setServiceConditions(
                    (new SspServiceConditionsTransfer())
                        ->setSspAssetReferences(array_map(function (SspAssetTransfer $sspAssetTransfer) {
                            return $sspAssetTransfer->getReferenceOrFail();
                        }, $sspAssetCollectionTransfer->getSspAssets()->getArrayCopy())),
                )
                ->setPagination(
                    (new PaginationTransfer())
                        ->setPage(static::SERVICE_PAGINATION_PAGE)
                        ->setMaxPerPage($sspAssetCriteriaTransfer->getInclude()->getWithServicesCount()),
                )
                ->setSortCollection(new ArrayObject([(new SortTransfer())->setDirection('asc')->setField('created_at')])),
        );

        foreach ($sspServiceCollectionTransfer->getServices() as $sspServiceTransfer) {
            foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
                foreach ($sspServiceTransfer->getSspAssets() as $serviceSspAssetTransfer) {
                    if ($sspAssetTransfer->getReferenceOrFail() === $serviceSspAssetTransfer->getReferenceOrFail()) {
                        if (!$sspAssetTransfer->getSspServiceCollection()) {
                            $sspAssetTransfer->setSspServiceCollection((new SspServiceCollectionTransfer())->setPagination($sspServiceCollectionTransfer->getPagination()));
                        }
                        $sspAssetTransfer->getSspServiceCollectionOrFail()->addService($sspServiceTransfer);
                    }
                }
            }
        }

        return $sspAssetCollectionTransfer;
    }
}
