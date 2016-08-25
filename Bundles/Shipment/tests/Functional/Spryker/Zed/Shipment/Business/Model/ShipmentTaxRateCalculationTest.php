<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Shipment\Business\Model;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrier;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxSetTax;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\Shipment\Business\ShipmentFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Shipment
 * @group Business
 * @group Model
 * @group ShipmentTaxRateCalculationTest
 */
class ShipmentTaxRateCalculationTest extends Test
{

    /**
     * @return void
     */
    public function testSetTaxRateWhenExemptTaxRateUsedShouldSetZeroTaxRate()
    {
        $shipmentMethodEntity = $this->createShipmentMethodWithTaxSet(20, 'DE');

        $quoteTransfer = new QuoteTransfer();

        $addressTransfer = new AddressTransfer();
        $addressTransfer->setIso2Code('GB');
        $quoteTransfer->setShippingAddress($addressTransfer);

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentMethodTransfer = new ShipmentMethodTransfer();
        $shipmentMethodTransfer->fromArray($shipmentMethodEntity->toArray(), true);
        $shipmentTransfer->setMethod($shipmentMethodTransfer);
        $quoteTransfer->setShipment($shipmentTransfer);

        $shipmentFacadeTest = $this->createShipmentFacade();
        $shipmentFacadeTest->calculateShipmentTaxRate($quoteTransfer);

        $this->assertEquals('0.0', $shipmentMethodTransfer->getTaxRate());
    }

    /**
     * @return void
     */
    public function testSetTaxRateWhenExemptTaxRateUsedAndCountryMatchingShouldUseCountryRate()
    {
        $shipmentMethodEntity = $this->createShipmentMethodWithTaxSet(20, 'DE');

        $quoteTransfer = new QuoteTransfer();

        $addressTransfer = new AddressTransfer();
        $addressTransfer->setIso2Code('DE');
        $quoteTransfer->setShippingAddress($addressTransfer);

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentMethodTransfer = new ShipmentMethodTransfer();
        $shipmentMethodTransfer->fromArray($shipmentMethodEntity->toArray(), true);
        $shipmentTransfer->setMethod($shipmentMethodTransfer);
        $quoteTransfer->setShipment($shipmentTransfer);

        $shipmentFacadeTest = $this->createShipmentFacade();
        $shipmentFacadeTest->calculateShipmentTaxRate($quoteTransfer);

        $this->assertEquals('20.00', $shipmentMethodTransfer->getTaxRate());
    }


    /**
     * @param int $taxRate
     * @param string $iso2Code
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethod
     */
    protected function createShipmentMethodWithTaxSet($taxRate, $iso2Code)
    {
        $countryEntity = SpyCountryQuery::create()->findOneByIso2Code($iso2Code);

        $taxRateEntity1 = new SpyTaxRate();
        $taxRateEntity1->setRate($taxRate);
        $taxRateEntity1->setName('test rate 1');
        $taxRateEntity1->setFkCountry($countryEntity->getIdCountry());
        $taxRateEntity1->save();

        $taxRateEntity2 = new SpyTaxRate();
        $taxRateEntity2->setRate(13);
        $taxRateEntity2->setName('tax rate 2');
        $taxRateEntity2->setFkCountry($countryEntity->getIdCountry());
        $taxRateEntity2->save();

        $taxRateExemptEntity = new SpyTaxRate();
        $taxRateExemptEntity->setRate(0);
        $taxRateExemptEntity->setName(TaxConstants::TAX_EXEMPT_PLACEHOLDER);
        $taxRateExemptEntity->save();

        $taxSetEntity = new SpyTaxSet();
        $taxSetEntity->setName('name of tax set');
        $taxSetEntity->save();

        $taxSetTaxRateEntity = new SpyTaxSetTax();
        $taxSetTaxRateEntity->setFkTaxSet($taxSetEntity->getIdTaxSet());
        $taxSetTaxRateEntity->setFkTaxRate($taxRateEntity1->getIdTaxRate());
        $taxSetTaxRateEntity->save();

        $taxSetTaxRateEntity = new SpyTaxSetTax();
        $taxSetTaxRateEntity->setFkTaxSet($taxSetEntity->getIdTaxSet());
        $taxSetTaxRateEntity->setFkTaxRate($taxRateEntity2->getIdTaxRate());
        $taxSetTaxRateEntity->save();

        $taxSetTaxRateEntity = new SpyTaxSetTax();
        $taxSetTaxRateEntity->setFkTaxSet($taxSetEntity->getIdTaxSet());
        $taxSetTaxRateEntity->setFkTaxRate($taxRateExemptEntity->getIdTaxRate());
        $taxSetTaxRateEntity->save();

        $shipmentCarrierEntity = new SpyShipmentCarrier();
        $shipmentCarrierEntity->setName('name carrier');
        $shipmentCarrierEntity->save();

        $shipmentMethodEntity = new SpyShipmentMethod();
        $shipmentMethodEntity->setFkShipmentCarrier($shipmentCarrierEntity->getIdShipmentCarrier());
        $shipmentMethodEntity->setFkTaxSet($taxSetEntity->getIdTaxSet());
        $shipmentMethodEntity->setName('test shipment method');
        $shipmentMethodEntity->save();

        return $shipmentMethodEntity;
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentFacade
     */
    protected function createShipmentFacade()
    {
        return new ShipmentFacade();
    }

}
