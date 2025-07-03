<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Persistence;

use Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer;
use Orm\Zed\ShipmentType\Persistence\Map\SpyShipmentTypeTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ClickAndCollectExample\Persistence\ClickAndCollectExamplePersistenceFactory getFactory()
 */
class ClickAndCollectExampleRepository extends AbstractRepository implements ClickAndCollectExampleRepositoryInterface
{
    /**
     * @var string
     */
    protected const ALIAS_PRODUCT_OFFER_STORE = 'aliasProductOfferStore';

    /**
     * @var string
     */
    protected const ALIAS_SERVICE_POINT_STORE = 'aliasServicePointStore';

    /**
     * @module ProductOffer
     * @module ProductOfferServicePoint
     * @module ServicePoint
     * @module Store
     * @module ShipmentType
     * @module ShipmentTypeServicePoint
     *
     * @param \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    public function getPickupProductOfferServicePointsByCriteria(ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer): array
    {
        $productOfferQuery = $this->getFactory()->getProductOfferQuery()
            ->useSpyProductOfferStoreQuery()
                ->useSpyStoreQuery(static::ALIAS_PRODUCT_OFFER_STORE)
                    ->filterByName($productOfferServicePointCriteriaTransfer->getStoreNameOrFail())
                ->endUse()
            ->endUse()
            ->useProductOfferServiceQuery()
                ->useServiceQuery()
                    ->useServicePointQuery()
                        ->filterByIdServicePoint_In($productOfferServicePointCriteriaTransfer->getServicePointIds())
                        ->filterByIsActive(true)
                        ->useServicePointStoreQuery()
                            ->useStoreQuery(static::ALIAS_SERVICE_POINT_STORE)
                                ->filterByName($productOfferServicePointCriteriaTransfer->getStoreNameOrFail())
                            ->endUse()
                        ->endUse()
                    ->endUse()
                    ->useServiceTypeQuery()
                        ->useSpyShipmentTypeServiceTypeQuery()
                            ->useSpyShipmentTypeQuery()
                                ->filterByKey($productOfferServicePointCriteriaTransfer->getShipmentTypeKeyOrFail())
                                ->filterByIsActive(true)
                            ->endUse()
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->filterByConcreteSku_In($productOfferServicePointCriteriaTransfer->getConcreteSkus());

        if ($productOfferServicePointCriteriaTransfer->getIsActive() === null || $productOfferServicePointCriteriaTransfer->getIsActive()) {
            $productOfferQuery->filterByIsActive(true);
        }

        $productOfferEntityCollection = $productOfferQuery->find();

        return $this->getFactory()->createProductOfferMapper()
            ->mapProductOfferEntityCollectionToProductOfferServicePointTransfers(
                $productOfferEntityCollection,
            );
    }

    /**
     * @module ProductOffer
     * @module ProductOfferShipmentType
     * @module Store
     * @module ShipmentType
     *
     * @param \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    public function getDeliveryProductOfferServicePointsByCriteria(ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer): array
    {
        $productOfferQuery = $this->getFactory()
            ->getProductOfferQuery()
            ->useSpyProductOfferStoreQuery()
                ->useSpyStoreQuery(static::ALIAS_PRODUCT_OFFER_STORE)
                    ->filterByName($productOfferServicePointCriteriaTransfer->getStoreNameOrFail())
                ->endUse()
            ->endUse()
            ->leftJoinWithProductOfferShipmentType()
            ->useProductOfferShipmentTypeQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinShipmentType()
                ->useShipmentTypeQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinShipmentTypeStore()
                    ->useShipmentTypeStoreQuery(null, Criteria::LEFT_JOIN)
                        ->leftJoinStore()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->condition('storeNameEquals', sprintf('%s = ?', SpyStoreTableMap::COL_NAME), $productOfferServicePointCriteriaTransfer->getStoreNameOrFail())
            ->condition('shipmentTypeKeyEquals', sprintf('%s = ?', SpyShipmentTypeTableMap::COL_KEY), $productOfferServicePointCriteriaTransfer->getShipmentTypeKeyOrFail())
            ->condition('shipmentTypeIsActive', sprintf('%s = ?', SpyShipmentTypeTableMap::COL_IS_ACTIVE), true)
            ->condition('idShipmentTypeIsNull', sprintf('%s IS NULL', SpyShipmentTypeTableMap::COL_ID_SHIPMENT_TYPE))
            ->combine(['shipmentTypeKeyEquals', 'shipmentTypeIsActive', 'storeNameEquals'], Criteria::LOGICAL_AND, 'shipmentTypeConditions')
            ->where(['idShipmentTypeIsNull', 'shipmentTypeConditions'], Criteria::LOGICAL_OR)
            ->filterByConcreteSku_In($productOfferServicePointCriteriaTransfer->getConcreteSkus());

        if ($productOfferServicePointCriteriaTransfer->getIsActive() === null || $productOfferServicePointCriteriaTransfer->getIsActive()) {
            $productOfferQuery->filterByIsActive(true);
        }

        $productOfferEntityCollection = $productOfferQuery->find();

        return $this->getFactory()->createProductOfferMapper()
            ->mapProductOfferEntityCollectionToProductOfferServicePointTransfers(
                $productOfferEntityCollection,
            );
    }

    /**
     * @module ProductOfferStock
     *
     * @param \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferStockTransfer>
     */
    public function getProductOfferStocksByCriteria(ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer): array
    {
        $productOfferStockQuery = $this->getFactory()->getProductOfferStockQuery()
            ->filterByFkProductOffer_In($productOfferServicePointCriteriaTransfer->getProductOfferIds());

        $productOfferStockEntityCollection = $productOfferStockQuery->find();

        return $this->getFactory()->createProductOfferStockMapper()
            ->mapProductOfferStockEntityCollectionToProductOfferStockTransfers(
                $productOfferStockEntityCollection,
            );
    }

    /**
     * @module PriceProductOffer
     * @module Store
     * @module Currency
     *
     * @param \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferPriceTransfer>
     */
    public function getProductOfferPricesByCriteria(ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer): array
    {
        $priceProductOfferQuery = $this->getFactory()->getPriceProductOfferQuery()
            ->joinWithSpyPriceProductStore()
            ->useSpyPriceProductStoreQuery()
                ->joinStore()
                ->useStoreQuery()
                    ->filterByName($productOfferServicePointCriteriaTransfer->getStoreNameOrFail())
                ->endUse()
                ->joinCurrency()
                ->useCurrencyQuery()
                    ->filterByCode($productOfferServicePointCriteriaTransfer->getCurrencyCodeOrFail())
                ->endUse()
            ->endUse()
            ->filterByFkProductOffer_In($productOfferServicePointCriteriaTransfer->getProductOfferIds());

        $priceProductOfferEntityCollection = $priceProductOfferQuery->find();

        return $this->getFactory()->createPriceProductOfferMapper()
            ->mapPriceProductOfferEntityCollectionToProductOfferPriceTransfers(
                $priceProductOfferEntityCollection,
                $productOfferServicePointCriteriaTransfer->getPriceModeOrFail(),
            );
    }
}
