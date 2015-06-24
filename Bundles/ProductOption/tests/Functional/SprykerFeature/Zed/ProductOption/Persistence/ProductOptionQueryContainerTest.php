<?php

namespace Functional\SprykerFeature\Zed\ProductOption\Persistence;

use Codeception\TestCase\Test;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\ProductOption\Persistence\ProductOptionQueryContainer;

/**
 * @group Business
 * @group Zed
 * @group ProdutOption
 * @group DataImportWriterTest
 *
 * (c) Spryker Systems GmbH copyright protected
 */
class ProductOptionQueryContainerTest extends Test
{

    /**
     * @var ProductOptionQueryContainer
     */
    private $queryContainer;

    /**
     * @var AutoCompletion $locator
     */
    protected $locator;

    /**
     * @var array
     */
    protected $ids = [];

    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();
        $this->queryContainer = $this->locator->productOption()->queryContainer();
        $this->ids = DbFixturesLoader::loadFixtures();
    }

    public function testQueryTypeUsagesForConcreteProduct()
    {
        $result = $this->queryContainer
            ->queryTypeUsagesForConcreteProduct($this->ids['idConcreteProduct'],  $this->ids['idLocale']);

            $this->assertCount(2, $result);
            $this->assertEquals('Color', $result[0]['label']);
    }

    public function testQueryValueUsagesForTypeUsage()
    {
        $result = $this->queryContainer
            ->queryValueUsagesForTypeUsage($this->ids['idUsageSize'], $this->ids['idLocale']);

        $this->assertCount(4, $result);
        $this->assertEquals('Large', $result[0]['label']);
        $this->assertEquals('199', $result[0]['price']);
        $this->assertEquals('Medium', $result[1]['label']);
        $this->assertNull($result[1]['price']);
    }

    public function testQueryTypeExclusionsForTypeUsage()
    {
        $result = $this->queryContainer
            ->queryTypeExclusionsForTypeUsage($this->ids['idUsageColor']);

        $this->assertCount(1, $result);
        $this->assertEquals($this->ids['idUsageSize'], $result[0]);
    }

    public function testQueryValueConstraintsForValueUsage()
    {
        $result = $this->queryContainer
            ->queryValueConstraintsForValueUsage($this->ids['idUsageGreen']);

        $this->assertCount(2, $result);
        $this->assertEquals('ALLOW', $result[0]['operator']);
        $this->assertEquals($this->ids['idUsageLarge'], $result[0]['valueUsageId']);
        $this->assertEquals('ALLOW', $result[1]['operator']);
        $this->assertEquals($this->ids['idUsageSmall'], $result[1]['valueUsageId']);

        $result = $this->queryContainer
            ->queryValueConstraintsForValueUsage($this->ids['idUsageBlue']);

        $this->assertCount(1, $result);
        $this->assertEquals('NOT', $result[0]['operator']);
        $this->assertEquals($this->ids['idUsageSmall'], $result[0]['valueUsageId']);

        $result = $this->queryContainer
            ->queryValueConstraintsForValueUsage($this->ids['idUsageMedium']);

        $this->assertCount(1, $result);
        $this->assertEquals('ALWAYS', $result[0]['operator']);
        $this->assertEquals($this->ids['idUsageRed'], $result[0]['valueUsageId']);
    }

    public function testQueryValueConstraintsForValueUsageByOperator()
    {
        $result = $this->queryContainer
            ->queryValueConstraintsForValueUsageByOperator($this->ids['idUsageGreen'], 'ALLOW');

        $this->assertCount(2, $result);
        $this->assertEquals($this->ids['idUsageLarge'], $result[0]);
        $this->assertEquals($this->ids['idUsageSmall'], $result[1]);

        $result = $this->queryContainer
            ->queryValueConstraintsForValueUsageByOperator($this->ids['idUsageGreen'], 'NOT');
        $this->assertEmpty($result);
    }

    public function testQueryConfigPresetsForConcreteProduct()
    {
        $result = $this->queryContainer
            ->queryConfigPresetsForConcreteProduct($this->ids['idConcreteProduct']);

        $this->assertCount(2, $result);
        $this->assertEquals(1, $result[0]['isDefault']);
    }

    public function testQueryValueUsagesForConfigPreset()
    {
        $result = $this->queryContainer
            ->queryValueUsagesForConfigPreset($this->ids['idConfigPresetA']);

        $this->assertCount(2, $result);
        $this->assertEquals($this->ids['idUsageRed'], $result[0]);
        $this->assertEquals($this->ids['idUsageMedium'], $result[1]);
    }

    public function testQueryEffectiveTaxRateForAbstractProduct()
    {
        $result = $this->queryContainer
            ->queryEffectiveTaxRateForAbstractProduct($this->ids['idAbstractProduct']);

        $this->assertEquals('15.00', $result);
    }

    public function testQueryEffectiveTaxRateForTypeUsage()
    {
        $result = $this->queryContainer
            ->queryEffectiveTaxRateForTypeUsage($this->ids['idUsageSize']);

        $this->assertEquals('15.00', $result);

        $result = $this->queryContainer
            ->queryEffectiveTaxRateForTypeUsage($this->ids['idUsageColor']);

        $this->assertNull($result);
    }
}
