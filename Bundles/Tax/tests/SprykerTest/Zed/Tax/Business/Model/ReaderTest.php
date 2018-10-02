<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Business\Model;

use Codeception\Test\Unit;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Spryker\Zed\Tax\Business\TaxFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Tax
 * @group Business
 * @group Model
 * @group ReaderTest
 * Add your own group annotations below this line
 */
class ReaderTest extends Unit
{
    public const DUMMY_TAX_SET_NAME = 'SalesTax';
    public const DUMMY_TAX_RATE1_NAME = 'Local';
    public const DUMMY_TAX_RATE1_PERCENTAGE = 25;
    public const DUMMY_TAX_RATE2_NAME = 'Regional';
    public const DUMMY_TAX_RATE2_PERCENTAGE = 10;
    public const NON_EXISTENT_ID = 999999999;

    /**
     * @var \Spryker\Zed\Tax\Business\TaxFacadeInterface
     */
    private $taxFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->taxFacade = new TaxFacade();
    }

    /**
     * @return void
     */
    public function testGetTaxRates()
    {
        $this->loadFixtures();
        $taxRateCollectionTransfer = $this->taxFacade->getTaxRates();
        $this->assertTrue(count($taxRateCollectionTransfer->getTaxRates()) > 0);
    }

    /**
     * @return void
     */
    public function testGetTaxRate()
    {
        $persistedTaxSet = $this->loadFixtures();
        $result = $this->taxFacade->getTaxRate($persistedTaxSet->getSpyTaxRates()[0]->getIdTaxRate());
        $this->assertEquals(self::DUMMY_TAX_RATE1_NAME, $result->getName());
        $this->assertEquals(self::DUMMY_TAX_RATE1_PERCENTAGE, $result->getRate());
    }

    /**
     * @return void
     */
    public function testTaxRateExists()
    {
        $persistedTaxSet = $this->loadFixtures();
        $result = $this->taxFacade->taxRateExists($persistedTaxSet->getSpyTaxRates()[0]->getIdTaxRate());
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testGetTaxSets()
    {
        $this->loadFixtures();
        $taxSetCollectionTransfer = $this->taxFacade->getTaxSets();
        $this->assertNotEmpty($taxSetCollectionTransfer->getTaxSets());
    }

    /**
     * @return void
     */
    public function testGetTaxSet()
    {
        $persistedTaxSet = $this->loadFixtures();
        $result = $this->taxFacade->getTaxSet($persistedTaxSet->getIdTaxSet());
        $this->assertEquals(self::DUMMY_TAX_SET_NAME, $result->getName());
    }

    /**
     * @return void
     */
    public function testTaxSetExists()
    {
        $persistedTaxSet = $this->loadFixtures();
        $result = $this->taxFacade->taxSetExists($persistedTaxSet->getIdTaxSet());
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testExceptionRaisedIfAttemptingToFetchNonExistentTaxRate()
    {
        $this->expectException('Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException');
        $this->taxFacade->getTaxSet(self::NON_EXISTENT_ID);
    }

    /**
     * @return void
     */
    public function testExceptionRaisedIfAttemptingToFetchNonExistentTaxSet()
    {
        $this->expectException('Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException');
        $this->taxFacade->getTaxRate(self::NON_EXISTENT_ID);
    }

    /**
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSet
     */
    private function loadFixtures()
    {
        $taxRateEntity = new SpyTaxRate();
        $taxRateEntity->setName(self::DUMMY_TAX_RATE1_NAME);
        $taxRateEntity->setRate(self::DUMMY_TAX_RATE1_PERCENTAGE);
        $taxRateEntity->save();

        $taxSetEntity = new SpyTaxSet();
        $taxSetEntity->setName(self::DUMMY_TAX_SET_NAME);
        $taxSetEntity->addSpyTaxRate($taxRateEntity);
        $taxSetEntity->save();

        return $taxSetEntity;
    }
}
