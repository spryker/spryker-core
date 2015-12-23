<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\ProductOption\Persistence;

use Functional\Spryker\Zed\ProductOption\Mock\LocaleFacade;
use Functional\Spryker\Zed\ProductOption\Mock\ProductFacade;
use Functional\Spryker\Zed\ProductOption\Mock\ProductOptionQueryContainer;
use Functional\Spryker\Zed\ProductOption\Mock\ProductQueryContainer;
use Spryker\Zed\Kernel\AbstractFunctionalTest;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Locale\Business\LocaleBusinessFactory;
use Spryker\Zed\Product\Business\ProductBusinessFactory;
use Spryker\Zed\Propel\Communication\Plugin\Connection;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductOption\Business\ProductOptionBusinessFactory;
use Spryker\Zed\ProductOption\Business\ProductOptionFacade;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToProductInterface;

/**
 * @group Business
 * @group Zed
 * @group ProductOption
 * @group ProductOptionReaderTest
 *
 * @method ProductOptionFacade getFacade()
 */
class ProductOptionReaderTest extends AbstractFunctionalTest
{

    const LOCALE_CODE = 'xx_XX';
    const PROPEL_CONNECTION = 'propel connection';
    const FACADE_PRODUCT = 'FACADE_PRODUCT';
    const FACADE_LOCALE = 'LOCALE_FACADE';
    const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';

    /**
     * @var array
     */
    protected $ids = [];

    /**
     * @var ProductOptionFacade
     */
    protected $facade;

    /**
     * @var ProductOptionToLocaleInterface
     */
    private $localeFacade;

    /**
     * @var ProductOptionToProductInterface
     */
    private $productFacade;

    /**
     * @var ProductQueryContainerInterface
     */
    private $productQueryContainer;

    /**
     * @var ProductOptionQueryContainer
     */
    private $productOptionQueryContainer;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->ids = DbFixturesLoader::loadFixtures();

        $this->facade = $this->getFacade();
        $this->facade->setFactory(new ProductOptionBusinessFactory());

        $this->localeFacade = new LocaleFacade();
        $this->localeFacade->setFactory(new LocaleBusinessFactory());

        $this->productFacade = new ProductFacade();
        $this->productFacade->setFactory(new ProductBusinessFactory());

        $this->productQueryContainer = new ProductQueryContainer();
        $this->productOptionQueryContainer = new ProductOptionQueryContainer();

        $this->buildProductOptionFacade();
    }

    /**
     * @return void
     */
    public function testGetProductOption()
    {
        $productOptionTransfer = $this->facade->getProductOption(
            $this->ids['idUsageLarge'],
            self::LOCALE_CODE
        );

        $this->assertEquals('Size', $productOptionTransfer->getLabelOptionType());
        $this->assertEquals('Large', $productOptionTransfer->getLabelOptionValue());
        $this->assertEquals(199, $productOptionTransfer->getGrossPrice());

        $taxSetTransfer = $productOptionTransfer->getTaxSet();

        $this->assertEquals('Baz', $taxSetTransfer->getName());

        $taxRateTransfer = $taxSetTransfer->getTaxRates()[0];
        $this->assertEquals('Foo', $taxRateTransfer->getName());
        $this->assertEquals('10', $taxRateTransfer->getRate());
    }

    /**
     * @return void
     */
    public function testQueryTypeUsagesForConcreteProduct()
    {
        $result = $this->facade
            ->getTypeUsagesForConcreteProduct($this->ids['idConcreteProduct'], $this->ids['idLocale']);

        $this->assertCount(2, $result);
        $this->assertEquals('Color', $result[0]['label']);
    }

    /**
     * When here an error occurs like "Failed asserting that 1559 matches expected 1557."
     * this test must be changed so that the sorting of the result doesn't matter or we have
     * to change the different behavoiur of mysql and postgres
     *
     * @return void
     */
    public function testQueryValueUsagesForTypeUsage()
    {
        $result = $this->facade
            ->getValueUsagesForTypeUsage($this->ids['idUsageSize'], $this->ids['idLocale']);

        $this->assertCount(4, $result);
        $this->assertEquals('Large', $result[0]['label']);
        $this->assertEquals('199', $result[0]['price']);
        $this->assertEquals('Medium', $result[1]['label']);
        $this->assertNull($result[1]['price']);
    }

    /**
     * @return void
     */
    public function testQueryTypeExclusionsForTypeUsage()
    {
        $result = $this->facade
            ->getTypeExclusionsForTypeUsage($this->ids['idUsageColor']);

        $this->assertCount(1, $result);
        $this->assertEquals($this->ids['idUsageSize'], $result[0]);
    }

    /**
     * @return void
     */
    public function testQueryValueConstraintsForValueUsage()
    {
        $this->markTestSkipped('Ordering of result always different');

        $result = $this->facade
            ->getValueConstraintsForValueUsage($this->ids['idUsageGreen']);

        $this->assertCount(2, $result);

        $this->assertEquals('ALLOW', $result[0]['operator']);
        $this->assertEquals($this->ids['idUsageSmall'], $result[0]['valueUsageId']);

        $this->assertEquals('ALLOW', $result[1]['operator']);
        $this->assertEquals($this->ids['idUsageLarge'], $result[1]['valueUsageId']);

        $result = $this->facade
            ->getValueConstraintsForValueUsage($this->ids['idUsageBlue']);

        $this->assertCount(1, $result);
        $this->assertEquals('NOT', $result[0]['operator']);
        $this->assertEquals($this->ids['idUsageSmall'], $result[0]['valueUsageId']);

        $result = $this->facade
            ->getValueConstraintsForValueUsage($this->ids['idUsageMedium']);

        $this->assertCount(1, $result);
        $this->assertEquals('ALWAYS', $result[0]['operator']);
        $this->assertEquals($this->ids['idUsageRed'], $result[0]['valueUsageId']);
    }

    /**
     * @return void
     */
    public function testQueryValueConstraintsForValueUsageByOperator()
    {
        $this->markTestSkipped('Ordering of result always different');

        $result = $this->facade
            ->getValueConstraintsForValueUsageByOperator($this->ids['idUsageGreen'], 'ALLOW');

        $this->assertCount(2, $result);
        $this->assertEquals($this->ids['idUsageSmall'], $result[0]);
        $this->assertEquals($this->ids['idUsageLarge'], $result[1]);

        $result = $this->facade
            ->getValueConstraintsForValueUsageByOperator($this->ids['idUsageGreen'], 'NOT');
        $this->assertEmpty($result);
    }

    /**
     * @return void
     */
    public function testQueryConfigPresetsForConcreteProduct()
    {
        $result = $this->facade
            ->getConfigPresetsForConcreteProduct($this->ids['idConcreteProduct']);

        $this->assertCount(2, $result);
        $this->assertEquals(1, $result[0]['isDefault']);
    }

    /**
     * @return void
     */
    public function testQueryValueUsagesForConfigPreset()
    {
        $result = $this->facade
            ->getValueUsagesForConfigPreset($this->ids['idConfigPresetA']);

        $this->assertCount(2, $result);
        $this->assertEquals($this->ids['idUsageRed'], $result[0]);
        $this->assertEquals($this->ids['idUsageMedium'], $result[1]);
    }

    /**
     * @return void
     */
    public function testQueryEffectiveTaxRateForTypeUsage()
    {
        $result = $this->facade
            ->getEffectiveTaxRateForTypeUsage($this->ids['idUsageSize']);

        $this->assertEquals('15.00', $result);

        $result = $this->facade
            ->getEffectiveTaxRateForTypeUsage($this->ids['idUsageColor']);

        $this->assertNull($result);
    }

    /**
     * @return void
     */
    protected function buildProductOptionFacade()
    {
        $container = new Container();
        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return $this->productFacade;
        };
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return $this->localeFacade;
        };
        $container[self::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return $this->productQueryContainer;
        };
        $container[self::PROPEL_CONNECTION] = function () {
            return (new Connection())->get();
        };
        $this->productOptionQueryContainer->setExternalDependencies($container);
        $this->facade->setExternalDependencies($container);
        $this->facade->setQueryContainer($this->productOptionQueryContainer);
    }

}
