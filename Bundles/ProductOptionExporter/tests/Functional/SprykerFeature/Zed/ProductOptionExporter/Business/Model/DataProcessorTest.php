<?php

namespace Functional\SprykerFeature\Zed\ProductOptionExporter\Business\Model;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\ProductOption\Business\ProductOptionFacade;
use Generated\Zed\Ide\AutoCompletion;
use Functional\SprykerFeature\Zed\ProductOption\Persistence\DbFixturesLoader;
use SprykerFeature\Zed\ProductOptionExporter\Business\ProductOptionExporterFacade;


/**
 * @group Business
 * @group Zed
 * @group ProdutOptionExporter
 * @group DataProcessorTest
 */
class DataProcessorTest extends Test
{

    /**
     * @var ProductOptionFacade
     */
    private $facade;

    /**
     * @var AutoCompletion $locator
     */
    protected $locator;

    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();
        $this->facade = new ProductOptionExporterFacade(new Factory('ProductOptionExporter'), $this->locator);
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
                        'sku' => 'DEF456'
                    ],
                ],
            ],
        ];

        $processedResultSet = $this->facade->processDataForExport($resultSetFixture, $processedResultSetFixture);

        $this->assertCount(1, $processedResultSet);
    }
}
