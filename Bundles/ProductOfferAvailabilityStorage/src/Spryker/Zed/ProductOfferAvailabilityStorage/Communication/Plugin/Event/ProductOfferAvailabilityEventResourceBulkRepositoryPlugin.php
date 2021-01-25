<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Event;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Shared\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceBulkRepositoryPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOffer\Dependency\ProductOfferEvents;

/**
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Business\ProductOfferAvailabilityStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Communication\ProductOfferAvailabilityStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig getConfig()
 */
class ProductOfferAvailabilityEventResourceBulkRepositoryPlugin extends AbstractPlugin implements EventResourceBulkRepositoryPluginInterface
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
        return ProductOfferAvailabilityStorageConfig::PRODUCT_OFFER_AVAILABILITY_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer[]
     */
    public function getData(int $offset, int $limit): array
    {
        $productOfferCriteriaFilterTransfer = (new ProductOfferCriteriaFilterTransfer())
            ->setPagination(
                (new PaginationTransfer())
                    ->setPage(($offset / $limit) + 1)
                    ->setMaxPerPage($limit)
            );

        $productOfferTransfers = $this->getFactory()
            ->getProductOfferFacade()
            ->find($productOfferCriteriaFilterTransfer)
            ->getProductOffers()
            ->getArrayCopy();

        if ($this->isOutOfPages($productOfferCriteriaFilterTransfer->getPagination())) {
            return [];
        }

        return $productOfferTransfers;
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
        return ProductOfferEvents::ENTITY_SPY_PRODUCT_OFFER_PUBLISH;
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
        return SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER;
    }

    /**
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return bool
     */
    protected function isOutOfPages(PaginationTransfer $paginationTransfer): bool
    {
        return $paginationTransfer->getNbResults()
            <= $paginationTransfer->getMaxPerPage() * ($paginationTransfer->getPage() - 1);
    }
}
