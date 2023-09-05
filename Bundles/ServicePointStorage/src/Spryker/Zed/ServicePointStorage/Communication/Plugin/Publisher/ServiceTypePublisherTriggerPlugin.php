<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ServiceTypeConditionsTransfer;
use Generated\Shared\Transfer\ServiceTypeCriteriaTransfer;
use Spryker\Shared\ServicePointStorage\ServicePointStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\ServicePointStorage\Business\ServicePointStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ServicePointStorage\ServicePointStorageConfig getConfig()
 * @method \Spryker\Zed\ServicePointStorage\Communication\ServicePointStorageCommunicationFactory getFactory()
 */
class ServiceTypePublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServiceTypeTableMap::COL_ID_SERVICE_TYPE
     *
     * @var string
     */
    protected const COL_ID_SERVICE_TYPE = 'spy_service_type.id_service_type';

    /**
     * {@inheritDoc}
     * - Retrieves service types by provided limit and offset.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return list<\Generated\Shared\Transfer\ServiceTypeTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())
            ->setServiceTypeConditions(new ServiceTypeConditionsTransfer())
            ->setPagination((new PaginationTransfer())
                ->setOffset($offset)
                ->setLimit($limit));

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\ServiceTypeTransfer> $serviceTypeTransfers */
        $serviceTypeTransfers = $this->getFactory()
            ->getServicePointFacade()
            ->getServiceTypeCollection($serviceTypeCriteriaTransfer)
            ->getServiceTypes();

        return $serviceTypeTransfers->getArrayCopy();
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
        return ServicePointStorageConfig::SERVICE_TYPE_RESOURCE_NAME;
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
        return ServicePointStorageConfig::SERVICE_TYPE_PUBLISH;
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
        return static::COL_ID_SERVICE_TYPE;
    }
}
