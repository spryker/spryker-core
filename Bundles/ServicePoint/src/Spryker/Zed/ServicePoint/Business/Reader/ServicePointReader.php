<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Reader;

use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Spryker\Zed\ServicePoint\Business\Expander\ServicePointStoreRelationExpanderInterface;
use Spryker\Zed\ServicePoint\Business\Expander\ServiceRelationExpanderInterface;
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
     * @var \Spryker\Zed\ServicePoint\Business\Expander\ServiceRelationExpanderInterface
     */
    protected ServiceRelationExpanderInterface $serviceRelationExpander;

    /**
     * @param \Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface $servicePointRepository
     * @param \Spryker\Zed\ServicePoint\Business\Expander\ServicePointStoreRelationExpanderInterface $servicePointStoreRelationExpander
     * @param \Spryker\Zed\ServicePoint\Business\Expander\ServiceRelationExpanderInterface $serviceRelationExpander
     */
    public function __construct(
        ServicePointRepositoryInterface $servicePointRepository,
        ServicePointStoreRelationExpanderInterface $servicePointStoreRelationExpander,
        ServiceRelationExpanderInterface $serviceRelationExpander
    ) {
        $this->servicePointRepository = $servicePointRepository;
        $this->servicePointStoreRelationExpander = $servicePointStoreRelationExpander;
        $this->serviceRelationExpander = $serviceRelationExpander;
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
        if (!$servicePointConditionsTransfer) {
            return $servicePointCollectionTransfer;
        }

        if ($servicePointConditionsTransfer->getWithStoreRelations()) {
            $servicePointCollectionTransfer = $this->servicePointStoreRelationExpander->expandServicePointCollectionWithStoreRelations(
                $servicePointCollectionTransfer,
            );
        }

        if ($servicePointConditionsTransfer->getWithServiceRelations()) {
            $servicePointCollectionTransfer = $this->serviceRelationExpander->expandServicePointCollectionWithServiceRelations(
                $servicePointCollectionTransfer,
            );
        }

        return $servicePointCollectionTransfer;
    }
}
