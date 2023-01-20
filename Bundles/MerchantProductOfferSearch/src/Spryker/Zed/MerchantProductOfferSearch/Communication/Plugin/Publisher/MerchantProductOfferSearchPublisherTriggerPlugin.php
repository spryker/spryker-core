<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Shared\MerchantProductOfferSearch\MerchantProductOfferSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferSearch\Communication\MerchantProductOfferSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOfferSearch\Business\MerchantProductOfferSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchConfig getConfig()
 */
class MerchantProductOfferSearchPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER
     *
     * @var string
     */
    public const COL_ID_PRODUCT_OFFER = 'spy_product_offer.id_product_offer';

    /**
     * {@inheritDoc}
     * - Retrieves collection of merchant product offers by offset and limit from Persistence.
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
        $merchantProductOfferCriteriaTransfer = $this->createMerchantProductOfferCriteriaTransfer($offset, $limit);

        return $this->getFactory()->getMerchantProductOfferFacade()
            ->getProductOfferCollection($merchantProductOfferCriteriaTransfer)->getProductOffers()->getArrayCopy();
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
        return MerchantProductOfferSearchConfig::MERCHANT_PRODUCT_OFFER_RESOURCE_NAME;
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
        return MerchantProductOfferSearchConfig::PRODUCT_OFFER_PUBLISH;
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
     * @return \Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer
     */
    protected function createMerchantProductOfferCriteriaTransfer(int $offset, int $limit): MerchantProductOfferCriteriaTransfer
    {
        return (new MerchantProductOfferCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setOffset($offset)->setLimit($limit),
            );
    }
}
