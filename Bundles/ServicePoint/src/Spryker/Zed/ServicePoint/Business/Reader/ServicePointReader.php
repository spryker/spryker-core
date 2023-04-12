<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Reader;

use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Spryker\Zed\ServicePoint\Business\Expander\ServicePointStoreRelationExpanderInterface;
use Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface;

class ServicePointReader implements ServicePointReaderInterface
{
    /**
     * @var \Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface
     */
    protected ServicePointRepositoryInterface $servicePointRepository;

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Expander\ServicePointStoreRelationExpanderInterface
     */
    protected ServicePointStoreRelationExpanderInterface $servicePointStoreRelationExpander;

    /**
     * @param \Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface $servicePointRepository
     * @param \Spryker\Zed\ServicePoint\Business\Expander\ServicePointStoreRelationExpanderInterface $servicePointStoreRelationExpander
     */
    public function __construct(
        ServicePointRepositoryInterface $servicePointRepository,
        ServicePointStoreRelationExpanderInterface $servicePointStoreRelationExpander
    ) {
        $this->servicePointRepository = $servicePointRepository;
        $this->servicePointStoreRelationExpander = $servicePointStoreRelationExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointCriteriaTransfer $servicePointCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionTransfer
     */
    public function getServicePointCollection(
        ServicePointCriteriaTransfer $servicePointCriteriaTransfer
    ): ServicePointCollectionTransfer {
        $servicePointCollectionTransfer = $this->servicePointRepository
            ->getServicePointCollection($servicePointCriteriaTransfer);

        $servicePointConditionsTransfer = $servicePointCriteriaTransfer->getServicePointConditions();

        if ($servicePointConditionsTransfer && $servicePointConditionsTransfer->getWithStoreRelations()) {
            return $this->servicePointStoreRelationExpander->expandServicePointCollectionWithStoreRelations(
                $servicePointCollectionTransfer,
            );
        }

        return $servicePointCollectionTransfer;
    }
}
