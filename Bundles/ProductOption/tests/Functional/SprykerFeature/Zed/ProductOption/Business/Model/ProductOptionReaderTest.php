<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\ProductOption\Persistence;

use Functional\SprykerFeature\Zed\ProductOption\Mock\LocaleFacade;
use Functional\SprykerFeature\Zed\ProductOption\Mock\ProductFacade;
use Functional\SprykerFeature\Zed\ProductOption\Mock\ProductOptionQueryContainer;
use Functional\SprykerFeature\Zed\ProductOption\Mock\ProductQueryContainer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Persistence\Factory as PersistenceFactory;
use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use Generated\Shared\Transfer\ProductOptionTransfer;
use SprykerEngine\Zed\Kernel\Business\Factory as BusinessFactory;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;
use SprykerFeature\Zed\ProductOption\Business\ProductOptionFacade;
use SprykerFeature\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface;
use SprykerFeature\Zed\ProductOption\Dependency\Facade\ProductOptionToProductInterface;

/**
 * @group Business
 * @group Zed
 * @group ProductOption
 * @group ProductOptionReaderTest
 */
class ProductOptionReaderTest extends AbstractFunctionalTest
{

    const LOCALE_CODE = 'xx_XX';
    const PROPEL_CONNECTION = 'propel connection';
    const FACADE_PRODUCT = 'FACADE_PRODUCT';
    const FACADE_LOCALE = 'FACADE_LOCALE';
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
     * @var Locator
     */
    private $locator;

    /**
     * @var AutoCompletion;
     */
    private $locatorAutoCompletion;

    /**
     * @var ProductOptionQueryContainer
     */
    private $productOptionQueryContainer;

    public function setUp()
    {
        parent::setUp();

        $this->ids = DbFixturesLoader::loadFixtures();
        $this->facade = $this->getFacade();
        $this->locator = Locator::getInstance();
        $this->localeFacade = new LocaleFacade(new BusinessFactory('Locale'), $this->locator);
        $this->productFacade = new ProductFacade(new BusinessFactory('Product'), $this->locator);
        $this->productQueryContainer = new ProductQueryContainer(new PersistenceFactory('Product'), $this->locator);
        $this->productOptionQueryContainer = new ProductOptionQueryContainer(
            new PersistenceFactory('ProductOption'),
            $this->locator
        );

        $this->buildProductOptionFacade();
    }

    public function testGetProductOption()
    {
        /** @var $productOptionTransfer ProductOptionTransfer */
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

    public function testQueryTypeUsagesForConcreteProduct()
    {
        $result = $this->facade
            ->getTypeUsagesForConcreteProduct($this->ids['idConcreteProduct'], $this->ids['idLocale']);

        $this->assertCount(2, $result);
        $this->assertEquals('Color', $result[0]['label']);
    }

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

    public function testQueryTypeExclusionsForTypeUsage()
    {
        $result = $this->facade
            ->getTypeExclusionsForTypeUsage($this->ids['idUsageColor']);

        $this->assertCount(1, $result);
        $this->assertEquals($this->ids['idUsageSize'], $result[0]);
    }

    public function testQueryValueConstraintsForValueUsage()
    {
        $result = $this->facade
            ->getValueConstraintsForValueUsage($this->ids['idUsageGreen']);

        $this->assertCount(2, $result);
        $this->assertEquals('ALLOW', $result[0]['operator']);
        $this->assertEquals($this->ids['idUsageLarge'], $result[0]['valueUsageId']);
        $this->assertEquals('ALLOW', $result[1]['operator']);
        $this->assertEquals($this->ids['idUsageSmall'], $result[1]['valueUsageId']);

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

    public function testQueryValueConstraintsForValueUsageByOperator()
    {
        $result = $this->facade
            ->getValueConstraintsForValueUsageByOperator($this->ids['idUsageGreen'], 'ALLOW');

        $this->assertCount(2, $result);
        $this->assertEquals($this->ids['idUsageLarge'], $result[0]);
        $this->assertEquals($this->ids['idUsageSmall'], $result[1]);

        $result = $this->facade
            ->getValueConstraintsForValueUsageByOperator($this->ids['idUsageGreen'], 'NOT');
        $this->assertEmpty($result);
    }

    public function testQueryConfigPresetsForConcreteProduct()
    {
        $result = $this->facade
            ->getConfigPresetsForConcreteProduct($this->ids['idConcreteProduct']);

        $this->assertCount(2, $result);
        $this->assertEquals(1, $result[0]['isDefault']);
    }

    public function testQueryValueUsagesForConfigPreset()
    {
        $result = $this->facade
            ->getValueUsagesForConfigPreset($this->ids['idConfigPresetA']);

        $this->assertCount(2, $result);
        $this->assertEquals($this->ids['idUsageRed'], $result[0]);
        $this->assertEquals($this->ids['idUsageMedium'], $result[1]);
    }

    public function testQueryEffectiveTaxRateForTypeUsage()
    {
        $result = $this->facade
            ->getEffectiveTaxRateForTypeUsage($this->ids['idUsageSize']);

        $this->assertEquals('15.00', $result);

        $result = $this->facade
            ->getEffectiveTaxRateForTypeUsage($this->ids['idUsageColor']);

        $this->assertNull($result);
    }

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
        $locator = Locator::getInstance();
        $container[self::PROPEL_CONNECTION] = function () use ($locator) {
            /** @var $locator AutoCompletion */
            return $locator->propel()->pluginConnection()->get();
        };
        $this->productOptionQueryContainer->setContainer($container);
        $this->facade->setExternalDependencies($container);
        $this->facade->setOwnQueryContainer($this->productOptionQueryContainer);
    }
}
