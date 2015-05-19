<?php

namespace Functional\SprykerFeature\Zed\Tax;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Tax\Business\TaxFacade;
use Generated\Zed\Ide\AutoCompletion;
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
    const DUMMY_TAX_RATE3_NAME = 'Federal';
    const DUMMY_TAX_RATE3_PERCENTAGE = 2;
    const DUMMY_TAX_RATE4_NAME = 'Liquor';
    const DUMMY_TAX_RATE4_PERCENTAGE = 8.25;

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
        // ...
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

    }
}