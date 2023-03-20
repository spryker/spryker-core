<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Spryker\Shared\CustomerAccessStorage\CustomerAccessStorageConfig;
use Spryker\Shared\CustomerAccessStorage\CustomerAccessStorageConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\CustomerAccessStorage\Business\CustomerAccessStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerAccessStorage\Communication\CustomerAccessStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CustomerAccessStorage\CustomerAccessStorageConfig getConfig()
 * @method \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageRepositoryInterface getRepository()
 */
class CustomerAccessPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\CustomerAccess\Persistence\Map\SpyUnauthenticatedCustomerAccessTableMap::COL_ID_UNAUTHENTICATED_CUSTOMER_ACCESS
     *
     * @var string
     */
    protected const COL_ID_UNAUTHENTICATED_CUSTOMER_ACCESS = 'spy_unauthenticated_customer_access.id_unauthenticated_customer_access';

    /**
     * {@inheritDoc}
     * - Returns an array with `ContentTypeAccessTransfer` to trigger event in case when `$offset === 0`.
     * - Returns empty array in case when `$offset !== 0`.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return list<\Generated\Shared\Transfer\ContentTypeAccessTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        return $offset === 0 ? [(new ContentTypeAccessTransfer())->setIdUnauthenticatedCustomerAccess(0)] : [];
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
        return CustomerAccessStorageConstants::CUSTOMER_ACCESS_RESOURCE_NAME;
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
        return CustomerAccessStorageConfig::UNAUTHENTICATED_CUSTOMER_ACCESS_PUBLISH;
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
        return static::COL_ID_UNAUTHENTICATED_CUSTOMER_ACCESS;
    }
}
