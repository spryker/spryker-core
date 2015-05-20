<?php

namespace Functional\SprykerFeature\Zed\Tax;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Tax\Business\TaxFacade;
use Generated\Zed\Ide\AutoCompletion;

/**
 * @group TaxTest
 */
class ReaderTest extends Test
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

    public function testGetTaxSet()
    {
        $persistedTaxSet = $this->loadFixtures();
        $result = $this->taxFacade->getTaxSet($persistedTaxSet->getIdTaxSet());
        $this->assertNotEmpty($result);
    }

    public function testGetTaxRate()
    {
        $persistedTaxSet = $this->loadFixtures();
        $result = $this->taxFacade->getTaxRate($persistedTaxSet->getSpyTaxRates()[0]->getIdTaxRate());
        $this->assertNotEmpty($result);
    }

    public function testTaxRateExists()
    {
        $persistedTaxSet = $this->loadFixtures();
        $result = $this->taxFacade->taxRateExists($persistedTaxSet->getSpyTaxRates()[0]->getIdTaxRate());
        $this->assertTrue($result);
    }

    public function testTaxSetExists()
    {
        $persistedTaxSet = $this->loadFixtures();
        $result = $this->taxFacade->taxSetExists($persistedTaxSet->getIdTaxSet());
        $this->assertTrue($result);
    }

    public function testExceptionRaisedIfAttemptingToFetchNonExistentTaxRate()
    {
        $this->setExpectedException('\Exception');
        $this->taxFacade->getTaxSet(9999999999);
    }

    public function testExceptionRaisedIfAttemptingToFetchNonExistentTaxSet()
    {
        $this->setExpectedException('\Exception');
        $this->taxFacade->getTaxRate(9999999999);
    }

    private function loadFixtures()
    {
        $taxRateTransfer = new TaxRateTransfer();
        $taxRateTransfer->setName(self::DUMMY_TAX_RATE1_NAME);
        $taxRateTransfer->setRate(self::DUMMY_TAX_RATE1_PERCENTAGE);

        $taxSetTransfer = new TaxSetTransfer();
        $taxSetTransfer->setName(self::DUMMY_TAX_SET_NAME);
        $taxSetTransfer->addTaxRate($taxRateTransfer);

        return $this->taxFacade->createTaxSet($taxSetTransfer);
    }
}