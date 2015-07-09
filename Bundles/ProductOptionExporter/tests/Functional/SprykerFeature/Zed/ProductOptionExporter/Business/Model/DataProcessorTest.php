<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\ProductOptionExporter\Business\Model;

use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use Generated\Zed\Ide\AutoCompletion;
use Functional\SprykerFeature\Zed\ProductOption\Persistence\DbFixturesLoader;
use SprykerFeature\Zed\ProductOptionExporter\Business\ProductOptionExporterFacade;
use Generated\Shared\Transfer\LocaleTransfer;

/**
 * @group Business
 * @group Zed
 * @group ProdutOptionExporter
 * @group DataProcessorTest
 *
 * @method ProductOptionExporterFacade getFacade()
 */
class DataProcessorTest extends AbstractFunctionalTest
{

    /**
     * @var ProductOptionExporterFacade
     */
    private $facade;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    public function setUp()
    {
        parent::setUp();

        $this->facade = $this->getFacade();
    }

    public function testFetchProductOptionDataForConcreteProduct()
    {
        $ids = DbFixturesLoader::loadFixtures();

        $resultSetFixture = [
            'xx.xx_xx.dummyIndex1' => [],
        ];

        $processedResultSetFixture = [
            'xx.xx_xx.dummyIndex1' => [
                'concrete_products' => [
                    [
                        'sku' => 'DEF456',
                    ],
                ],
            ],
        ];

        $localeTransfer = (new LocaleTransfer())->setIdLocale($ids['idLocale']);

        $processedResultSet = $this->facade->processDataForExport($resultSetFixture, $processedResultSetFixture, $localeTransfer);

        $this->assertCount(1, $processedResultSet);
        $product = $processedResultSet['xx.xx_xx.dummyIndex1']['concrete_products'][0];
        $this->assertEquals('DEF456', $product['sku']);

        $this->assertCount(2, $product['configs']);
        $config = $product['configs'][0];
        $this->assertEquals($ids['idUsageRed'], $config['values'][0]);
        $this->assertEquals($ids['idUsageMedium'], $config['values'][1]);

        $this->assertCount(2, $product['options']);
        $option = $product['options'][0];
        $this->assertEquals('Color', $option['label']);
        $this->assertFalse($option['isOptional']);
        $this->assertEquals('15.0', $option['taxRate']);
        $this->assertEquals($ids['idUsageSize'], $option['excludes'][0]);

        $this->assertCount(4, $option['values']);
        $value = $option['values'][3];
        $this->assertEquals('Green', $value['label']);
        $this->assertNull($value['price']);

        $this->assertArrayHasKey('allow', $value['constraints']);
        $allowConstraints = $value['constraints']['allow'];
        $this->assertCount(2, $allowConstraints);
        $this->assertEquals($ids['idUsageLarge'], $allowConstraints[0]);

        $this->assertEquals('199', $product['options'][1]['values'][0]['price']);
    }

}
