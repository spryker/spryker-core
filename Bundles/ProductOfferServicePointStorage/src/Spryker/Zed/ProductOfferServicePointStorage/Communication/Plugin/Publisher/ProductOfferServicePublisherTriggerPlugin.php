<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer;
use Spryker\Shared\ProductOfferServicePointStorage\ProductOfferServicePointStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferServicePointStorage\Business\ProductOfferServicePointStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferServicePointStorage\Communication\ProductOfferServicePointStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageConfig getConfig()
 */
class ProductOfferServicePublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\ProductOfferServicePoint\Persistence\Map\SpyProductOfferServiceTableMap::COL_ID_PRODUCT_OFFER_SERVICE
     *
     * @var string
     */
    protected const COL_ID_PRODUCT_OFFER_SERVICE = 'spy_product_offer_service.id_product_offer_service';

    /**
     * {@inheritDoc}
     * - Retrieves product offer services by provided limit and offset.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferServicesTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())
            ->setPagination((new PaginationTransfer())
                ->setOffset($offset)
                ->setLimit($limit));

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferServicesTransfer> $productOfferServicesTransfers */
        $productOfferServicesTransfers = $this->getFactory()
            ->getProductOfferServicePointFacade()
            ->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer)
            ->getProductOfferServices();

        return $productOfferServicesTransfers->getArrayCopy();
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
        return ProductOfferServicePointStorageConfig::PRODUCT_OFFER_SERVICE_RESOURCE_NAME;
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
        return ProductOfferServicePointStorageConfig::PRODUCT_OFFER_SERVICE_PUBLISH;
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
        return static::COL_ID_PRODUCT_OFFER_SERVICE;
    }
}
