<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\TaxProductConnector\Business\Plugin;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\TaxProductConnector\Business\TaxProductConnectorFacade;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxRate;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSet;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchQuery;

/**
 * @group Business
 * @group Zed
 * @group TaxProductConnector
 * @group TaxChangeTouchPluginTest
 */
class TaxChangeTouchPluginTest extends Test
{

    private $taxRateIds = [];
    private $taxSetId = null;
    private $abstractProductIds = [];

    /**
     * @var TaxProductConnectorFacade
     */
    private $taxProductConnectorFacade;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();
        $this->taxProductConnectorFacade = new TaxProductConnectorFacade(
            new Factory('TaxProductConnector'),
            $this->locator
        );
    }

    public function testTouchUpdatedOnTaxRateChange()
    {
        $this->loadFixtures();
        $this->taxProductConnectorFacade->getTaxChangeTouchPlugin()->handleTaxRateChange($this->taxRateIds[0]);
        $this->performAssertion();
    }

    public function testTouchUpdatedOnTaxSetChange()
    {
        $this->loadFixtures();
        $this->taxProductConnectorFacade->getTaxChangeTouchPlugin()->handleTaxSetChange($this->taxSetId);
        $this->performAssertion();
    }

    private function performAssertion()
    {
        $query = SpyTouchQuery::create()
            ->filterByItemType('abstract_product')
            ->limit(2)
            ->orderByIdTouch('desc')
            ->find();

        $this->assertEquals(2, $query->count());

        foreach($query as $touchEntity) {
            $this->assertContains($touchEntity->getItemId(), $this->abstractProductIds);
        }
    }

    private function loadFixtures()
    {
        $rate1 = new SpyTaxRate();
        $rate1->setName('Rate1')
            ->setRate(10)
            ->save()
        ;
        $this->taxRateIds[] = $rate1->getIdTaxRate();

        $rate2 = new SpyTaxRate();
        $rate2->setName('Rate2')
            ->setRate(5)
            ->save()
        ;
        $this->taxRateIds[] = $rate2->getIdTaxRate();

        $taxSet = new SpyTaxSet();
        $taxSet->setName('Set1')
            ->addSpyTaxRate($rate1)
            ->addSpyTaxRate($rate2)
            ->save()
        ;
        $this->taxSetId = $taxSet->getIdTaxSet();

        $product1 = new SpyAbstractProduct();
        $product1->setSku('Product1')
            ->setSpyTaxSet($taxSet)
            ->setAttributes('{}')
            ->save()
        ;
        $this->abstractProductIds[] = $product1->getIdAbstractProduct();

        $product2 = new SpyAbstractProduct();
        $product2->setSku('Product2')
            ->setSpyTaxSet($taxSet)
            ->setAttributes('{}')
            ->save()
        ;
        $this->abstractProductIds[] = $product2->getIdAbstractProduct();
    }

}
