<?php

namespace Functional\SprykerFeature\Zed\ProductOptionExporter\Business\Model;

use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use Generated\Zed\Ide\AutoCompletion;
use Functional\SprykerFeature\Zed\ProductOption\Persistence\DbFixturesLoader;
use SprykerFeature\Zed\ProductOptionExporter\Business\ProductOptionExporterFacade;


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
     * @var AutoCompletion $locator
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
                        'sku' => 'DEF456'
                    ],
                ],
            ],
        ];

        $processedResultSet = $this->facade->processDataForExport($resultSetFixture, $processedResultSetFixture);

        $this->assertCount(1, $processedResultSet);
    }
}
