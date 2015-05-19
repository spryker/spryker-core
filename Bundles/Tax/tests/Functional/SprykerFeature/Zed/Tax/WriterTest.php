<?php

namespace Functional\SprykerFeature\Zed\Tax;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\TaxRateTransfer;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Tax\Business\TaxFacade;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Tax\Persistence\Propel\Base\SpyTaxSet;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxRateQuery;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSetQuery;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSetTaxQuery;

/**
 * @group TaxTest
 */
class WriterTest extends Test
{
    const DUMMY_TAX_SET_NAME = 'CaliforniaLiquorTax';
    const DUMMY_TAX_RATE1_NAME = 'State';
    const DUMMY_TAX_RATE1_PERCENTAGE = 2.5;
    const DUMMY_TAX_RATE2_NAME = 'County';
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
        $this->setTestData();
    }

    public function testCreateTaxRate()
    {
        $taxRateTransfer = new TaxRateTransfer();
        $taxRateTransfer->setName(self::DUMMY_TAX_RATE1_NAME);
        $taxRateTransfer->setRate(self::DUMMY_TAX_RATE1_PERCENTAGE);

        $taxRateEntity = $this->taxFacade->createTaxRate($taxRateTransfer);

        $taxRateQuery = SpyTaxRateQuery::create()->filterByName($taxRateEntity->getName())->findOne();

        $this->assertNotEmpty($taxRateQuery);
    }

    public function testCreateTaxSet()
    {
        // ...
    }

    public function testDeleteTaxRate()
    {
        // ...
    }

    public function testDeleteTaxSet()
    {
        // ...
    }

    private function setTestData()
    {
//        $taxRate1 = SpyTaxRateQuery::create()->filterByIdTaxRate(1)->findOneOrCreate();
//        $taxRate1->setName(self::DUMMY_TAX_RATE1_NAME);
//        $taxRate1->setRate(self::DUMMY_TAX_RATE1_PERCENTAGE);
//        $taxRate1->save();
//
//        $taxRate2 = SpyTaxRateQuery::create()->filterByIdTaxRate(2)->findOneOrCreate();
//        $taxRate2->setName(self::DUMMY_TAX_RATE2_NAME);
//        $taxRate2->setRate(self::DUMMY_TAX_RATE2_PERCENTAGE);
//        $taxRate2->save();
//
//        $taxSet = SpyTaxSetQuery::create()->filterByIdTaxSet(1)->findOneOrCreate();
//        $taxSet->setName(self::DUMMY_TAX_SET_NAME)->addSpyTaxRate($taxRate1)->addSpyTaxRate($taxRate2)->save();

    }
}