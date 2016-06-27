<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Tax\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxSetTax;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\Tax\Business\TaxFacade;

class TaxFacadeRateCalculationTest extends Test
{
    /**
     * @return void
     */
    public function testSetTaxRateWhenExemptTaxRateUsedShouldSetZeroTaxRate()
    {
        $abstractProcuctEntity = $this->createAbstractProductWithTaxSet(20, 'GB');

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setIdProductAbstract($abstractProcuctEntity->getIdProductAbstract());
        $quoteTransfer->addItem($itemTransfer);

        $taxFacadeTest = $this->createTaxFacade();
        $taxFacadeTest->calculateProductItemTaxRate($quoteTransfer);

        $this->assertEquals('0.0', $itemTransfer->getTaxRate());
    }

    /**
     * @return void
     */
    public function testSetTaxRateWhenExemptTaxRateUsedAndCountryMatchingShouldUseCountryRate()
    {
        $abstractProcuctEntity = $this->createAbstractProductWithTaxSet(20, 'DE');

        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setIdProductAbstract($abstractProcuctEntity->getIdProductAbstract());
        $quoteTransfer->addItem($itemTransfer);

        $taxFacadeTest = $this->createTaxFacade();
        $taxFacadeTest->calculateProductItemTaxRate($quoteTransfer);

        $this->assertEquals('20.00', $itemTransfer->getTaxRate());
    }


    /**
     * @param int $taxRate
     * @param string $iso2Code
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function createAbstractProductWithTaxSet($taxRate, $iso2Code)
    {
        $countryEntity = SpyCountryQuery::create()->findOneByIso2Code($iso2Code);

        $taxRateEntity = new SpyTaxRate();
        $taxRateEntity->setRate($taxRate);
        $taxRateEntity->setName('test rate');
        $taxRateEntity->setFkCountry($countryEntity->getIdCountry());
        $taxRateEntity->save();

        $taxRateExemptEntity = new SpyTaxRate();
        $taxRateExemptEntity->setRate(0);
        $taxRateExemptEntity->setName(TaxConstants::TAX_EXEMPT_PLACEHOLDER);
        $taxRateExemptEntity->save();

        $taxSetEntity = new SpyTaxSet();
        $taxSetEntity->setName('name of tax set');
        $taxSetEntity->save();

        $taxSetTaxRateEntity = new SpyTaxSetTax();
        $taxSetTaxRateEntity->setFkTaxSet($taxSetEntity->getIdTaxSet());
        $taxSetTaxRateEntity->setFkTaxRate($taxRateEntity->getIdTaxRate());
        $taxSetTaxRateEntity->save();

        $taxSetTaxRateEntity = new SpyTaxSetTax();
        $taxSetTaxRateEntity->setFkTaxSet($taxSetEntity->getIdTaxSet());
        $taxSetTaxRateEntity->setFkTaxRate($taxRateExemptEntity->getIdTaxRate());
        $taxSetTaxRateEntity->save();

        $abstractProductEntity = new SpyProductAbstract();
        $abstractProductEntity->setSku('test-abstract-sku');
        $abstractProductEntity->setAttributes('');
        $abstractProductEntity->setFkTaxSet($taxSetEntity->getIdTaxSet());
        $abstractProductEntity->save();

        return $abstractProductEntity;
    }

    /**
     * @return \Spryker\Zed\Tax\Business\TaxFacade
     */
    protected function createTaxFacade()
    {
        return new TaxFacade();
    }
}
