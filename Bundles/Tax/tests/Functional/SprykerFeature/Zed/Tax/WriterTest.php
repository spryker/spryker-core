<?php

namespace Functional\SprykerFeature\Zed\Tax;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Tax\Business\TaxFacade;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxRateQuery;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSetQuery;

/**
 * @group TaxTest
 */
class WriterTest extends Test
{

    const DUMMY_TAX_SET_NAME = 'SalesTax';
    const DUMMY_TAX_RATE1_NAME = 'Local';
    const DUMMY_TAX_RATE1_PERCENTAGE = 2.5;
    const DUMMY_TAX_RATE2_NAME = 'Regional';
    const DUMMY_TAX_RATE2_PERCENTAGE = 10;

    /**
     * @var TaxFacade
     */
    private $taxFacade;

    /**
     * @var AutoCompletion $locator
     */
    protected $locator;

    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();
        $this->taxFacade = new TaxFacade(new Factory('Tax'), $this->locator);
    }

    public function testCreateTaxRate()
    {
        $taxRateTransfer = new TaxRateTransfer();
        $taxRateTransfer->setName(self::DUMMY_TAX_RATE1_NAME);
        $taxRateTransfer->setRate(self::DUMMY_TAX_RATE1_PERCENTAGE);

        $id = $this->taxFacade->createTaxRate($taxRateTransfer);

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($id)->findOne();

        $this->assertNotEmpty($taxRateQuery);
        $this->assertEquals(self::DUMMY_TAX_RATE1_PERCENTAGE, $taxRateQuery->getRate());
        $this->assertEquals(self::DUMMY_TAX_RATE1_NAME, $taxRateQuery->getName());

    }

    public function testCreateTaxSetWithNewTaxRate()
    {
        $taxSetTransfer = new TaxSetTransfer();
        $taxSetTransfer->setName(self::DUMMY_TAX_SET_NAME);

        $taxRateTransfer = new TaxRateTransfer();
        $taxRateTransfer->setName(self::DUMMY_TAX_RATE1_NAME);
        $taxRateTransfer->setRate(self::DUMMY_TAX_RATE1_PERCENTAGE);

        $taxSetTransfer->addTaxRate($taxRateTransfer);

        $id = $this->taxFacade->createTaxSet($taxSetTransfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($id)->findOne();

        $this->assertNotEmpty($taxSetQuery);
        $this->assertEquals(self::DUMMY_TAX_SET_NAME, $taxSetQuery->getName());
        $this->assertNotEmpty($taxSetQuery->getSpyTaxRates());
    }

    public function testCreateTaxSetWithExistingTaxRate()
    {
        $taxRateTransfer = new TaxRateTransfer();
        $taxRateTransfer->setName(self::DUMMY_TAX_RATE1_NAME);
        $taxRateTransfer->setRate(self::DUMMY_TAX_RATE1_PERCENTAGE);
        $id = $this->taxFacade->createTaxRate($taxRateTransfer);
        $taxRateTransfer->setIdTaxRate($id);

        $taxSetTransfer = new TaxSetTransfer();
        $taxSetTransfer->setName(self::DUMMY_TAX_SET_NAME);
        $taxSetTransfer->addTaxRate($taxRateTransfer);
        $id = $this->taxFacade->createTaxSet($taxSetTransfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($id)->findOne();

        $this->assertNotEmpty($taxSetQuery);
        $this->assertNotEmpty($taxSetQuery->getSpyTaxRates());
    }

    public function testDeleteTaxRate()
    {
        $taxRateTransfer = new TaxRateTransfer();
        $taxRateTransfer->setName(self::DUMMY_TAX_RATE1_NAME);
        $taxRateTransfer->setRate(self::DUMMY_TAX_RATE1_PERCENTAGE);
        $id = $this->taxFacade->createTaxRate($taxRateTransfer);

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($id)->findOne();
        $this->assertNotEmpty($taxRateQuery);

        $this->taxFacade->deleteTaxRate($id);

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($id)->findOne();
        $this->assertEmpty($taxRateQuery);
    }

    public function testDeleteTaxSetShouldDeleteSetButNotTheAssociatedRate()
    {
        $taxRateTransfer = new TaxRateTransfer();
        $taxRateTransfer->setName(self::DUMMY_TAX_RATE1_NAME);
        $taxRateTransfer->setRate(self::DUMMY_TAX_RATE1_PERCENTAGE);
        $rateId = $this->taxFacade->createTaxRate($taxRateTransfer);
        $taxRateTransfer->setIdTaxRate($rateId);

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($rateId)->findOne();
        $this->assertNotEmpty($taxRateQuery);

        $taxSetTransfer = new TaxSetTransfer();
        $taxSetTransfer->setName(self::DUMMY_TAX_SET_NAME);
        $taxSetTransfer->addTaxRate($taxRateTransfer);
        $setId = $this->taxFacade->createTaxSet($taxSetTransfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($setId)->findOne();
        $this->assertNotEmpty($taxSetQuery);

        $this->taxFacade->deleteTaxSet($setId);

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($rateId)->findOne();
        $this->assertNotEmpty($taxRateQuery);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($setId)->findOne();
        $this->assertEmpty($taxSetQuery);
    }
}