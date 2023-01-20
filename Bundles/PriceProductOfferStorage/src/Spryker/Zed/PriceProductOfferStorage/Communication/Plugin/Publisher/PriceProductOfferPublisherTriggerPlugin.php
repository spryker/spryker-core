<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Spryker\Shared\PriceProductOfferStorage\PriceProductOfferStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductOfferStorage\Communication\PriceProductOfferStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductOfferStorage\PriceProductOfferStorageConfig getConfig()
 */
class PriceProductOfferPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\PriceProductOffer\Persistence\Map\SpyPriceProductOfferTableMap::COL_ID_PRICE_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_ID_PRICE_PRODUCT_OFFER = 'spy_price_product_offer.id_price_product_offer';

    /**
     * {@inheritDoc}
     * - Retrieves collection of price product offers by offset and limit from Persistence.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Spryker\Shared\Kernel\Transfer\AbstractTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $priceProductStoreCriteriaTransfer = $this->createPriceProductOfferCriteriaTransfer($offset, $limit);

        return $this->getFactory()->getPriceProductOfferFacade()
            ->getPriceProductOfferCollection($priceProductStoreCriteriaTransfer)
            ->getPriceProductOffers()->getArrayCopy();
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
        return PriceProductOfferStorageConfig::PRICE_PRODUCT_OFFER_RESOURCE_NAME;
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
        return PriceProductOfferStorageConfig::ENTITY_SPY_PRICE_PRODUCT_OFFER_PUBLISH;
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
        return static::COL_ID_PRICE_PRODUCT_OFFER;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer
     */
    protected function createPriceProductOfferCriteriaTransfer(int $offset, int $limit): PriceProductOfferCriteriaTransfer
    {
        return (new PriceProductOfferCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setOffset($offset)->setLimit($limit),
            );
    }
}
