<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\ProductOption\Persistence;

use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use Generated\Shared\Transfer\ProductOptionTransfer;

/**
 * @group Business
 * @group Zed
 * @group ProdutOption
 * @group ProductOptionReaderTest
 */
class ProductOptionReaderTest extends AbstractFunctionalTest
{

    const LOCALE_CODE = 'xx_XX';

    /**
     * @var array
     */
    protected $ids = [];

    public function setUp()
    {
        parent::setUp();

        $this->ids = DbFixturesLoader::loadFixtures();
    }

    public function testGetProductOption()
    {
        /** @var $productOptionTransfer ProductOptionTransfer */
        $productOptionTransfer = $this->getFacade()->getProductOption(
            $this->ids['idUsageLarge'],
            self::LOCALE_CODE
        );

        $this->assertEquals('Size', $productOptionTransfer->getLabelOptionType());
        $this->assertEquals('Large', $productOptionTransfer->getLabelOptionValue());
        $this->assertEquals(199, $productOptionTransfer->getPrice());

        $taxSetTransfer = $productOptionTransfer->getTaxSet();

        $this->assertEquals('Baz', $taxSetTransfer->getName());

        $taxRateTransfer = $taxSetTransfer->getTaxRates()[0];
        $this->assertEquals('Foo', $taxRateTransfer->getName());
        $this->assertEquals('10', $taxRateTransfer->getRate());
    }

    public function testQueryTypeUsagesForConcreteProduct()
    {
        $result = $this->getFacade()
            ->getTypeUsagesForConcreteProduct($this->ids['idConcreteProduct'], $this->ids['idLocale']);

        $this->assertCount(2, $result);
        $this->assertEquals('Color', $result[0]['label']);
    }

    public function testQueryValueUsagesForTypeUsage()
    {
        $result = $this->getFacade()
            ->getValueUsagesForTypeUsage($this->ids['idUsageSize'], $this->ids['idLocale']);

        $this->assertCount(4, $result);
        $this->assertEquals('Large', $result[0]['label']);
        $this->assertEquals('199', $result[0]['price']);
        $this->assertEquals('Medium', $result[1]['label']);
        $this->assertNull($result[1]['price']);
    }

    public function testQueryTypeExclusionsForTypeUsage()
    {
        $result = $this->getFacade()
            ->getTypeExclusionsForTypeUsage($this->ids['idUsageColor']);

        $this->assertCount(1, $result);
        $this->assertEquals($this->ids['idUsageSize'], $result[0]);
    }

    public function testQueryValueConstraintsForValueUsage()
    {
        $result = $this->getFacade()
            ->getValueConstraintsForValueUsage($this->ids['idUsageGreen']);

        $this->assertCount(2, $result);
        $this->assertEquals('ALLOW', $result[0]['operator']);
        $this->assertEquals($this->ids['idUsageLarge'], $result[0]['valueUsageId']);
        $this->assertEquals('ALLOW', $result[1]['operator']);
        $this->assertEquals($this->ids['idUsageSmall'], $result[1]['valueUsageId']);

        $result = $this->getFacade()
            ->getValueConstraintsForValueUsage($this->ids['idUsageBlue']);

        $this->assertCount(1, $result);
        $this->assertEquals('NOT', $result[0]['operator']);
        $this->assertEquals($this->ids['idUsageSmall'], $result[0]['valueUsageId']);

        $result = $this->getFacade()
            ->getValueConstraintsForValueUsage($this->ids['idUsageMedium']);

        $this->assertCount(1, $result);
        $this->assertEquals('ALWAYS', $result[0]['operator']);
        $this->assertEquals($this->ids['idUsageRed'], $result[0]['valueUsageId']);
    }

    public function testQueryValueConstraintsForValueUsageByOperator()
    {
        $result = $this->getFacade()
            ->getValueConstraintsForValueUsageByOperator($this->ids['idUsageGreen'], 'ALLOW');

        $this->assertCount(2, $result);
        $this->assertEquals($this->ids['idUsageLarge'], $result[0]);
        $this->assertEquals($this->ids['idUsageSmall'], $result[1]);

        $result = $this->getFacade()
            ->getValueConstraintsForValueUsageByOperator($this->ids['idUsageGreen'], 'NOT');
        $this->assertEmpty($result);
    }

    public function testQueryConfigPresetsForConcreteProduct()
    {
        $result = $this->getFacade()
            ->getConfigPresetsForConcreteProduct($this->ids['idConcreteProduct']);

        $this->assertCount(2, $result);
        $this->assertEquals(1, $result[0]['isDefault']);
    }

    public function testQueryValueUsagesForConfigPreset()
    {
        $result = $this->getFacade()
            ->getValueUsagesForConfigPreset($this->ids['idConfigPresetA']);

        $this->assertCount(2, $result);
        $this->assertEquals($this->ids['idUsageRed'], $result[0]);
        $this->assertEquals($this->ids['idUsageMedium'], $result[1]);
    }

    public function testQueryEffectiveTaxRateForTypeUsage()
    {
        $result = $this->getFacade()
            ->getEffectiveTaxRateForTypeUsage($this->ids['idUsageSize']);

        $this->assertEquals('15.00', $result);

        $result = $this->getFacade()
            ->getEffectiveTaxRateForTypeUsage($this->ids['idUsageColor']);

        $this->assertNull($result);
    }

}
