<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Communication\Plugin\Event;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Orm\Zed\MerchantProfile\Persistence\Map\SpyMerchantProfileTableMap;
use Spryker\Shared\MerchantProfileStorage\MerchantProfileStorageConfig;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceBulkRepositoryPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantProfile\Dependency\MerchantProfileEvents;

/**
 * @method \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProfileStorage\Business\MerchantProfileStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProfileStorage\Communication\MerchantProfileStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig getConfig()
 */
class MerchantProfileEventResourceBulkRepositoryPlugin extends AbstractPlugin implements EventResourceBulkRepositoryPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return MerchantProfileStorageConfig::MERCHANT_PROFILE_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    public function getData(int $offset, int $limit): array
    {
        $filterTransfer = $this->createFilterTransfer($offset, $limit);
        $merchantProfileCriteriaFilterTransfer = $this->createMerchantProfileCriteriaFilterTransfer($filterTransfer);
        $merchantCollectionTransfer = $this->getFactory()
            ->getMerchantProfileFacade()
            ->find($merchantProfileCriteriaFilterTransfer);

        return $merchantCollectionTransfer->getMerchantProfiles()
            ->getArrayCopy();
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
        return MerchantProfileEvents::ENTITY_SPY_MERCHANT_PROFILE_PUBLISH;
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
        return SpyMerchantProfileTableMap::COL_ID_MERCHANT_PROFILE;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(int $offset, int $limit): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOrderBy(SpyMerchantProfileTableMap::COL_ID_MERCHANT_PROFILE)
            ->setOffset($offset)
            ->setLimit($limit);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer
     */
    protected function createMerchantProfileCriteriaFilterTransfer(FilterTransfer $filterTransfer): MerchantProfileCriteriaFilterTransfer
    {
        return (new MerchantProfileCriteriaFilterTransfer())
            ->setFilter($filterTransfer);
    }
}
