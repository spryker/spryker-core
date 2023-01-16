<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferStorage\ProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferStorage\Business\ProductOfferStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferStorage\Communication\ProductOfferStorageCommunicationFactory getFactory()
 */
class ProductOfferPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_ID_PRODUCT_OFFER = 'spy_product_offer.id_product_offer';

    /**
     * {@inheritDoc}
     * - Retrieves product offer collection by offset and limit from Persistence.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $productOfferCriteriaTransfer = $this->createProductOfferCriteriaTransfer($offset, $limit);

        return $this->getFactory()
            ->getProductOfferFacade()
            ->getProductOfferCollection($productOfferCriteriaTransfer)
            ->getProductOffers()
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
        return ProductOfferStorageConfig::RESOURCE_PRODUCT_OFFER_NAME;
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
        return ProductOfferStorageConfig::PRODUCT_OFFER_PUBLISH;
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
        return static::COL_ID_PRODUCT_OFFER;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaTransfer
     */
    protected function createProductOfferCriteriaTransfer(int $offset, int $limit): ProductOfferCriteriaTransfer
    {
        return (new ProductOfferCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())
                    ->setOffset($offset)
                    ->setLimit($limit),
            );
    }
}
