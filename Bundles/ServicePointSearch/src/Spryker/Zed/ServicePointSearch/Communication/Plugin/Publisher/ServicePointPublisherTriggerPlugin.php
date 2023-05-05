<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Spryker\Shared\ServicePointSearch\ServicePointSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\ServicePointSearch\ServicePointSearchConfig getConfig()
 * @method \Spryker\Zed\ServicePointSearch\Business\ServicePointSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ServicePointSearch\Communication\ServicePointSearchCommunicationFactory getFactory()
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
        $paginationTransfer = (new PaginationTransfer())
            ->setOffset($offset)
            ->setLimit($limit);

        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setPagination($paginationTransfer);

        return $this->getFactory()
            ->getServicePointFacade()
            ->getServicePointCollection($servicePointCriteriaTransfer)
            ->getServicePoints()
            ->getArrayCopy();
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
        return ServicePointSearchConfig::SERVICE_POINT_RESOURCE_NAME;
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
        return ServicePointSearchConfig::SERVICE_POINT_PUBLISH;
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
