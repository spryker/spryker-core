<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Tax;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Tax\Business\Model\Exception\ResourceNotFoundException;
use SprykerFeature\Zed\Tax\Business\TaxFacade;
use Generated\Zed\Ide\AutoCompletion;
use Orm\Zed\Tax\Persistence\SpyTaxRateQuery;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;

/**
 * @group Business
 * @group Zed
 * @group Tax
 * @group WriterTest
 */
class WriterTest extends Test
{

    const DUMMY_TAX_SET_NAME = 'SalesTax';
    const DUMMY_TAX_RATE1_NAME = 'Local';
    const DUMMY_TAX_RATE1_PERCENTAGE = 25;
    const DUMMY_TAX_RATE2_NAME = 'Regional';
    const DUMMY_TAX_RATE2_PERCENTAGE = 10;
    const NON_EXISTENT_ID = 999999999;

    /**
     * @var TaxFacade
     */
    private $taxFacade;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();
        $this->taxFacade = new TaxFacade(new Factory('Tax'), $this->locator);
    }

    private function createTaxRateTransfer()
    {
        $taxRateTransfer = new TaxRateTransfer();
        $taxRateTransfer->setName(self::DUMMY_TAX_RATE1_NAME);
        $taxRateTransfer->setRate(self::DUMMY_TAX_RATE1_PERCENTAGE);

        return $taxRateTransfer;
    }

    private function createTaxSetTransfer()
    {
        $taxSetTransfer = new TaxSetTransfer();
        $taxSetTransfer->setName(self::DUMMY_TAX_SET_NAME);

        return $taxSetTransfer;
    }

    public function testCreateTaxRate()
    {
        $taxRateTransfer = $this->createTaxRateTransfer();

        $this->taxFacade->createTaxRate($taxRateTransfer);

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($taxRateTransfer->getIdTaxRate())->findOne();

        $this->assertNotEmpty($taxRateQuery);
        $this->assertEquals(self::DUMMY_TAX_RATE1_PERCENTAGE, $taxRateQuery->getRate());
        $this->assertEquals(self::DUMMY_TAX_RATE1_NAME, $taxRateQuery->getName());
    }

    public function testCreateTaxSetWithNewTaxRate()
    {
        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxRateTransfer = $this->createTaxRateTransfer();

        $taxSetTransfer->addTaxRate($taxRateTransfer);

        $this->taxFacade->createTaxSet($taxSetTransfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($taxSetTransfer->getIdTaxSet())->findOne();

        $this->assertNotEmpty($taxSetQuery);
        $this->assertEquals(self::DUMMY_TAX_SET_NAME, $taxSetQuery->getName());
        $this->assertNotEmpty($taxSetQuery->getSpyTaxRates());
    }

    public function testCreateTaxSetWithExistingTaxRate()
    {
        $taxRateTransfer = $this->createTaxRateTransfer();
        $this->taxFacade->createTaxRate($taxRateTransfer);

        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->addTaxRate($taxRateTransfer);
        $this->taxFacade->createTaxSet($taxSetTransfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($taxSetTransfer->getIdTaxSet())->findOne();

        $this->assertNotEmpty($taxSetQuery);
        $this->assertNotEmpty($taxSetQuery->getSpyTaxRates());
    }

    public function testUpdateTaxRate()
    {
        $taxRateTransfer = $this->createTaxRateTransfer();
        $id = $this->taxFacade->createTaxRate($taxRateTransfer)->getIdTaxRate();

        $taxRateTransfer = new TaxRateTransfer();
        $taxRateTransfer->setIdTaxRate($id);
        $taxRateTransfer->setName(self::DUMMY_TAX_RATE2_NAME);
        $taxRateTransfer->setRate(self::DUMMY_TAX_RATE2_PERCENTAGE);

        $this->taxFacade->updateTaxRate($taxRateTransfer);

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($id)->findOne();

        $this->assertNotEmpty($taxRateQuery);
        $this->assertEquals(self::DUMMY_TAX_RATE2_PERCENTAGE, $taxRateQuery->getRate());
        $this->assertEquals(self::DUMMY_TAX_RATE2_NAME, $taxRateQuery->getName());
    }

    public function testUpdateTaxSet()
    {
        $taxRateTransfer = $this->createTaxRateTransfer();
        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->addTaxRate($taxRateTransfer);
        $taxSetId = $this->taxFacade->createTaxSet($taxSetTransfer)->getIdTaxSet();

        $taxRate2Transfer = new TaxRateTransfer();
        $taxRate2Transfer->setName(self::DUMMY_TAX_RATE2_NAME);
        $taxRate2Transfer->setRate(self::DUMMY_TAX_RATE2_PERCENTAGE);

        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->setIdTaxSet($taxSetId)->setName('Foobar');
        $taxSetTransfer->addTaxRate($taxRate2Transfer);

        $this->taxFacade->updateTaxSet($taxSetTransfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($taxSetId)->findOne();

        $this->assertNotEmpty($taxSetQuery);
        $this->assertEquals('Foobar', $taxSetQuery->getName());
        $this->assertCount(1, $taxSetQuery->getSpyTaxRates());
        $taxRateEntity = $taxSetQuery->getSpyTaxRates()[0];
        $this->assertEquals(self::DUMMY_TAX_RATE2_PERCENTAGE, $taxRateEntity->getRate());
        $this->assertEquals(self::DUMMY_TAX_RATE2_NAME, $taxRateEntity->getName());
    }

    public function testAddTaxRateToTaxSet()
    {
        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->addTaxRate($this->createTaxRateTransfer());
        $taxSetId = $this->taxFacade->createTaxSet($taxSetTransfer)->getIdTaxSet();

        $taxRate2Transfer = new TaxRateTransfer();
        $taxRate2Transfer->setName(self::DUMMY_TAX_RATE2_NAME);
        $taxRate2Transfer->setRate(self::DUMMY_TAX_RATE2_PERCENTAGE);

        $this->taxFacade->addTaxRateToTaxSet($taxSetId, $taxRate2Transfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($taxSetId)->findOne();

        $this->assertNotEmpty($taxSetQuery);
        $this->assertCount(2, $taxSetQuery->getSpyTaxRates());
        $this->assertEquals(self::DUMMY_TAX_RATE2_PERCENTAGE, $taxSetQuery->getSpyTaxRates()[1]->getRate());
    }

    public function testRemoveTaxRateFromTaxSet()
    {
        $taxRate1Transfer = $this->createTaxRateTransfer();
        $rate1Id = $this->taxFacade->createTaxRate($taxRate1Transfer)->getIdTaxRate();

        $taxRate2Transfer = new TaxRateTransfer();
        $taxRate2Transfer->setName(self::DUMMY_TAX_RATE2_NAME);
        $taxRate2Transfer->setRate(self::DUMMY_TAX_RATE2_PERCENTAGE);
        $rate2Id = $this->taxFacade->createTaxRate($taxRate2Transfer)->getIdTaxRate();

        $taxSetTransfer = $this->createTaxSetTransfer();

        $taxSetTransfer->addTaxRate($taxRate1Transfer);
        $taxSetTransfer->addTaxRate($taxRate2Transfer);

        $taxSetId = $this->taxFacade->createTaxSet($taxSetTransfer)->getIdTaxSet();

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($taxSetId);
        $taxSetEntity = $taxSetQuery->findOne();
        $this->assertCount(2, $taxSetEntity->getSpyTaxRates());

        $this->taxFacade->removeTaxRateFromTaxSet($taxSetId, $rate2Id);

        $taxSetEntity = $taxSetQuery->findOne();
        $this->assertCount(1, $taxSetEntity->getSpyTaxRates());
        $this->assertEquals($rate1Id, $taxSetEntity->getSpyTaxRates()[0]->getIdTaxRate());
    }

    public function testExceptionRaisedIfAttemptingToCreateTaxSetWithoutAnyTaxRates()
    {
        $this->setExpectedException('SprykerFeature\Zed\Tax\Business\Model\Exception\MissingTaxRateException');

        $this->taxFacade->createTaxSet($this->createTaxSetTransfer());
    }

    public function testExceptionRaisedIfAttemptingToUpdateNonExistentTaxRate()
    {
        $taxRateTransfer = $this->createTaxRateTransfer();
        $taxRateTransfer->setIdTaxRate(self::NON_EXISTENT_ID);

        $exceptionOccurred = false;
        try {
            $this->taxFacade->updateTaxRate($taxRateTransfer);
        } catch (ResourceNotFoundException $e) {
            $exceptionOccurred = true;
        }
        $this->assertTrue($exceptionOccurred);
    }

    public function testExceptionRaisedIfAttemptingToRemoveTaxRateFromTaxSetWithSingleTaxRate()
    {
        $this->setExpectedException('SprykerFeature\Zed\Tax\Business\Model\Exception\MissingTaxRateException');

        $taxRateTransfer = $this->createTaxRateTransfer();
        $rateId = $this->taxFacade->createTaxRate($taxRateTransfer)->getIdTaxRate();

        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->addTaxRate($taxRateTransfer);
        $taxSetId = $this->taxFacade->createTaxSet($taxSetTransfer)->getIdTaxSet();

        $this->taxFacade->removeTaxRateFromTaxSet($taxSetId, $rateId);
    }

    public function testDeleteTaxRate()
    {
        $id = $this->taxFacade->createTaxRate($this->createTaxRateTransfer())->getIdTaxRate();

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($id);

        $taxRateEntity = $taxRateQuery->findOne();
        $this->assertNotEmpty($taxRateEntity);

        $this->taxFacade->deleteTaxRate($id);

        $taxRateEntity = $taxRateQuery->findOne();
        $this->assertEmpty($taxRateEntity);
    }

    public function testDeleteTaxSetShouldDeleteSetButNotTheAssociatedRate()
    {
        $taxRateTransfer = $this->createTaxRateTransfer();
        $rateId = $this->taxFacade->createTaxRate($taxRateTransfer)->getIdTaxRate();
        $taxRateTransfer->setIdTaxRate($rateId);

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($rateId);
        $taxRateEntity = $taxRateQuery->findOne();
        $this->assertNotEmpty($taxRateEntity);

        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->addTaxRate($taxRateTransfer);
        $setId = $this->taxFacade->createTaxSet($taxSetTransfer)->getIdTaxSet();

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($setId);
        $taxSetEntity = $taxSetQuery->findOne();
        $this->assertNotEmpty($taxSetEntity);

        $this->taxFacade->deleteTaxSet($setId);

        $taxRateEntity = $taxRateQuery->findOne();
        $this->assertNotEmpty($taxRateEntity);

        $taxSetEntity = $taxSetQuery->findOne();
        $this->assertEmpty($taxSetEntity);
    }

}
