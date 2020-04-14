<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Spryker\Shared\MerchantStorage\MerchantStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\MerchantStorage\Communication\MerchantStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantStorage\Business\MerchantStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantStorage\MerchantStorageConfig getConfig()
 */
class MerchantPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
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
        return MerchantStorageConfig::MERCHANT_RESOURCE_NAME;
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
        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())->setFilter($filterTransfer);
        $merchantCollectionTransfer = $this->getFactory()
            ->getMerchantFacade()
            ->get($merchantCriteriaTransfer);

        return $merchantCollectionTransfer->getMerchants()->getArrayCopy();
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
        return MerchantEvents::MERCHANT_PUBLISH;
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
        return SpyMerchantTableMap::COL_ID_MERCHANT;
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
            ->setOrderBy(SpyMerchantTableMap::COL_ID_MERCHANT)
            ->setOffset($offset)
            ->setLimit($limit);
    }
}
