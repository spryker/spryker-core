<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ProductOptionExporter\Business\Model;

use Codeception\TestCase\Test;
use Functional\Spryker\Zed\ProductOption\Persistence\DbFixturesLoader;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductOptionExporter\Business\ProductOptionExporterFacade;

/**
 * @group Business
 * @group Zed
 * @group ProdutOptionExporter
 * @group DataProcessorTest
 *
 * @method \Spryker\Zed\ProductOptionExporter\Business\ProductOptionExporterFacade getFacade()
 */
class DataProcessorTest extends Test
{

    /**
     * @var \Spryker\Zed\ProductOptionExporter\Business\ProductOptionExporterFacade
     */
    private $facade;

    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->facade = new ProductOptionExporterFacade();
    }

    /**
     * @return void
     */
    public function testFetchProductOptionDataForProductConcrete()
    {
        $ids = DbFixturesLoader::loadFixtures();

        $resultSetFixture = [
            'xx.xx_xx.dummyIndex1' => [],
        ];

        $processedResultSetFixture = [
            'xx.xx_xx.dummyIndex1' => [
                'product_concrete_collection' => [
                    [
                        'sku' => 'DEF456',
                    ],
                ],
            ],
        ];

        $localeTransfer = (new LocaleTransfer())->setIdLocale($ids['idLocale']);

        $processedResultSet = $this->facade->processDataForExport($resultSetFixture, $processedResultSetFixture, $localeTransfer);

        $this->assertCount(1, $processedResultSet);
        $product = $processedResultSet['xx.xx_xx.dummyIndex1']['product_concrete_collection'][0];
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
