<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ShipmentDataImport;

use Codeception\Actor;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodStoreQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ShipmentDataImportCommunicationTester extends Actor
{
    use _generated\ShipmentDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureShipmentMethodStoreTableIsEmpty(): void
    {
        SpyShipmentMethodStoreQuery::create()->deleteAll();
    }

    /**
     * @return void
     */
    public function ensureShipmentMethodTableIsEmpty(): void
    {
        $this->ensureShipmentMethodPriceTableIsEmpty();
        $this->ensureShipmentMethodStoreTableIsEmpty();
        SpyShipmentMethodQuery::create()->deleteAll();
    }

    /**
     * @return void
     */
    public function ensureShipmentMethodPriceTableIsEmpty(): void
    {
        SpyShipmentMethodPriceQuery::create()->deleteAll();
    }

    /**
     * @return void
     */
    public function ensureShipmentCarrierTableIsEmpty(): void
    {
        SpyShipmentCarrierQuery::create()->deleteAll();
    }
}
