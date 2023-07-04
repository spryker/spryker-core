<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer;
use Spryker\Shared\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\ProductOfferShipmentTypeStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Communication\ProductOfferShipmentTypeStorageCommunicationFactory getFactory()
 */
class ProductOfferShipmentTypePublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\ProductOfferShipmentType\Persistence\Map\SpyProductOfferShipmentTypeTableMap::COL_ID_PRODUCT_OFFER_SHIPMENT_TYPE
     *
     * @var string
     */
    protected const COL_ID_PRODUCT_OFFER_SHIPMENT_TYPE = 'spy_product_offer_shipment_type.id_product_offer_shipment_type';

    /**
     * {@inheritDoc}
     * - Retrieves product offer shipment types by provided limit and offset.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $paginationTransfer = (new PaginationTransfer())
            ->setOffset($offset)
            ->setLimit($limit);
        $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer())
            ->setPagination($paginationTransfer);

        return $this->getFactory()
            ->getProductOfferShipmentTypeFacade()
            ->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer)
            ->getProductOfferShipmentTypes()
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
        return ProductOfferShipmentTypeStorageConfig::PRODUCT_OFFER_SHIPMENT_TYPE_RESOURCE_NAME;
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
        return ProductOfferShipmentTypeStorageConfig::PRODUCT_OFFER_SHIPMENT_TYPE_PUBLISH;
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
        return static::COL_ID_PRODUCT_OFFER_SHIPMENT_TYPE;
    }
}
