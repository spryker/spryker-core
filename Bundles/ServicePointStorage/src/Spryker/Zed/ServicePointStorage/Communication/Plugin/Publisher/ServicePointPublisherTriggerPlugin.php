<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ServicePointConditionsTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Spryker\Shared\ServicePointStorage\ServicePointStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\ServicePointStorage\Business\ServicePointStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ServicePointStorage\Communication\ServicePointStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ServicePointStorage\ServicePointStorageConfig getConfig()
 */
class ServicePointPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServicePointTableMap::COL_ID_SERVICE_POINT
     *
     * @var string
     */
    protected const COL_ID_SERVICE_POINT = 'spy_service_point.id_service_point';

    /**
     * {@inheritDoc}
     * - Retrieves service points by provided limit and offset.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\ServicePointTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions(new ServicePointConditionsTransfer())
            ->setPagination((new PaginationTransfer())
                ->setOffset($offset)
                ->setLimit($limit));

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers */
        $servicePointTransfers = $this->getFactory()
            ->getServicePointFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer)
            ->getServicePoints();

        return $servicePointTransfers->getArrayCopy();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ServicePointStorageConfig::SERVICE_POINT_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return ServicePointStorageConfig::SERVICE_POINT_PUBLISH;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return static::COL_ID_SERVICE_POINT;
    }
}
