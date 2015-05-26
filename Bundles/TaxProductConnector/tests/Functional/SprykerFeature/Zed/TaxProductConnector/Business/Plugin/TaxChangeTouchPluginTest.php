<?php

namespace Functional\SprykerFeature\Zed\TaxProductConnector\Business\Plugin;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\TaxProductConnector\Business\TaxProductConnectorFacade;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxRate;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSet;
use Propel\Runtime\Propel;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;


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
     * @var AutoCompletion $locator
     */
    protected $locator;

    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();
        $this->taxProductConnectorFacade = new TaxProductConnectorFacade(new Factory('TaxProductConnector'), $this->locator);
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
        $conn = Propel::getConnection();
        $stmt = $conn->prepare("SELECT item_id FROM spy_touch ORDER BY id_touch DESC LIMIT 2");
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        $this->assertCount(2, $result);
        $this->assertContains((string) $this->abstractProductIds[0], $result);
        $this->assertContains((string) $this->abstractProductIds[1], $result);
    }

    private function loadFixtures()
    {
        $rate1 = new SpyTaxRate();
        $rate1->setName('Rate1')->setRate(10)->save();
        $this->taxRateIds[] = $rate1->getIdTaxRate();

        $rate2 = new SpyTaxRate();
        $rate2->setName('Rate2')->setRate(5)->save();
        $this->taxRateIds[] = $rate2->getIdTaxRate();

        $taxSet = new SpyTaxSet();
        $taxSet->setName('Set1')->addSpyTaxRate($rate1)->addSpyTaxRate($rate2)->save();
        $this->taxSetId = $taxSet->getIdTaxSet();

        $product1 = new SpyAbstractProduct();
        $product1->setSku('Product1')->setSpyTaxSet($taxSet)->save();
        $this->abstractProductIds[] = $product1->getIdAbstractProduct();

        $product2 = new SpyAbstractProduct();
        $product2->setSku('Product2')->setSpyTaxSet($taxSet)->save();
        $this->abstractProductIds[] = $product2->getIdAbstractProduct();
    }
}
