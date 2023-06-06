<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductOfferShipmentTypeDataImport;

use Codeception\Actor;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\ProductOfferShipmentType\Persistence\Map\SpyProductOfferShipmentTypeTableMap;
use Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery;
use Orm\Zed\ShipmentType\Persistence\Map\SpyShipmentTypeTableMap;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Spryker\Zed\ProductOfferShipmentTypeDataImport\Communication\Plugin\DataImport\ProductOfferShipmentTypeDataImportPlugin;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOfferShipmentTypeDataImportCommunicationTester extends Actor
{
    use _generated\ProductOfferShipmentTypeDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function cleanUpData(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getProductOfferShipmentTypeQuery());
        $this->ensureDatabaseTableIsEmpty($this->getShipmentTypeQuery());
        $this->ensureDatabaseTableIsEmpty($this->getProductOfferQuery());
    }

    /**
     * @param list<string> $shipmentTypeKeys
     *
     * @return int
     */
    public function getProductOfferShipmentTypeEntitiesByShipmentTypeKeysCount(array $shipmentTypeKeys): int
    {
        $productOfferShipmentTypeQuery = $this->getProductOfferShipmentTypeQuery();

         return $this->getProductOfferShipmentTypeQuery()
             ->addJoin(
                 SpyProductOfferShipmentTypeTableMap::COL_SHIPMENT_TYPE_UUID,
                 SpyShipmentTypeTableMap::COL_UUID,
                 Criteria::INNER_JOIN,
             )
             ->addAnd($productOfferShipmentTypeQuery->getNewCriterion(
                 SpyShipmentTypeTableMap::COL_KEY,
                 $shipmentTypeKeys,
                 Criteria::IN,
             ))
             ->find()
             ->count();
    }

    /**
     * @return \Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery
     */
    public function getProductOfferShipmentTypeQuery(): SpyProductOfferShipmentTypeQuery
    {
        return SpyProductOfferShipmentTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery
     */
    public function getShipmentTypeQuery(): SpyShipmentTypeQuery
    {
        return SpyShipmentTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    public function getProductOfferQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentTypeDataImport\Communication\Plugin\DataImport\ProductOfferShipmentTypeDataImportPlugin
     */
    public function createProductOfferShipmentTypeDataImportPlugin(): ProductOfferShipmentTypeDataImportPlugin
    {
        return new ProductOfferShipmentTypeDataImportPlugin();
    }
}
