<?php


namespace Functional\SprykerFeature\Zed\ProductFrontendExporterConnector;

use Codeception\TestCase\Test;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Shared\Category\Transfer\Category;
use SprykerFeature\Shared\Category\Transfer\CategoryNode;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryAttributeQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryClosureTableQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNodeQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryQuery;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Library\Propel\Formatter\PropelArraySetFormatter;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Pyz\Zed\Locale\Business\LocaleFacade;
use Pyz\Zed\Product\Business\ProductFacade;
use Pyz\Zed\ProductCategory\Business\ProductCategoryFacade;
use Pyz\Zed\Touch\Business\TouchFacade;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchQuery;
use SprykerFeature\Zed\Url\Business\UrlFacade;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyUrlQuery;

/**
 * @group SprykerFeature
 * @group Zed
 * @group ProductFrontendExporterConnector
 * @group ProductFrontendExporterPlugin
 */
class ProductFrontendExporterPluginTest extends Test
{
    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var ProductFacade
     */
    protected $productFacade;

    /**
     * @var CategoryFacade
     */
    protected $categoryFacade;

    /**
     * @var TouchFacade
     */
    protected $touchFacade;

    /**
     * @var UrlFacade
     */
    protected $urlFacade;

    /**
     * @var ProductCategoryFacade
     */
    protected $productCategoryFacade;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @var int
     */
    protected $idLocale;

    /**
     * @group Exporter
     */
    public function testSoleProductExporter()
    {
        $this->createAttributeType();
        $idProduct = $this->createProduct('TestSku', 'TestProductName', $this->idLocale);
        $this->urlFacade->createUrl('/some-url', $this->localeName, 'product', $idProduct);
        $this->touchFacade->touchActive('test', $idProduct);

        $this->doExporterTest(
            [   //expanders
                $this->locator->productFrontendExporterConnector()->pluginProductQueryExpanderPlugin()
            ],
            [   //processors
                $this->locator->productFrontendExporterConnector()->pluginProductProcessorPlugin()
            ],
            [
                'de.abcde.resource.product.' . $idProduct =>
                    [
                        'sku' => 'TestSku',
                        'attributes' =>
                            [
                                'image_url' => '/images/product/robot_buttons_black.png',
                                'thumbnail_url' => '/images/product/default.png',
                                'price' => 1395,
                                'width' => 12,
                                'height' => 27,
                                'depth' => 850,
                                'main_color' => 'gray',
                                'other_colors' => 'red',
                                'weight' => 1.2,
                                'material' => 'aluminium',
                                'gender' => 'b',
                                'age' => 8,
                                'description' => 'A description!',
                                'name' => 'Ted Technical Robot',
                                'available' => true,
                            ],
                        'name' => 'TestProductName',
                        'url' => '/some-url'
                    ]
            ]
        );
    }

    /**
     *
     */
    protected function createAttributeType()
    {
        if (!$this->productFacade->hasAttributeType('test')) {
            $this->productFacade->createAttributeType('test', 'test');
        }
    }

    /**
     * @param string $sku
     * @param string $name
     * @param int $idLocale
     *
     * @return int
     */
    protected function createProduct($sku, $name, $idLocale)
    {
        $idAbstractProduct = $this->createAbstractProductWithAttributes('Abstract' . $sku, 'Abstract' . $name, $idLocale);
        $idConcreteProduct = $this->createConcreteProductWithAttributes($idAbstractProduct, $sku, $name, $idLocale);

        return $idConcreteProduct;
    }

    /**
     * @param string $sku
     * @param string $name
     * @param int $idLocale
     *
     * @return int
     */
    protected function createAbstractProductWithAttributes($sku, $name, $idLocale)
    {
        $idAbstractProduct = $this->productFacade->createAbstractProduct($sku);

        $this->productFacade->createAbstractProductAttributes(
            $idAbstractProduct,
            $idLocale,
            $name,
            json_encode(
                [
                    'thumbnail_url' => '/images/product/default.png',
                    'price' => 1395,
                    'width' => 12,
                    'height' => 27,
                    'depth' => 850,
                    'main_color' => 'gray',
                    'other_colors' => 'red',
                    'description' => 'A description!',
                    'name' => 'Ted Technical Robot',
                ]
            )
        );

        return $idAbstractProduct;
    }

    /**
     * @param int $idAbstractProduct
     * @param string $sku
     * @param string $name
     * @param int $idLocale
     * @return int
     */
    protected function createConcreteProductWithAttributes($idAbstractProduct, $sku, $name, $idLocale)
    {
        $idConcreteProduct = $this->productFacade->createConcreteProduct($sku, $idAbstractProduct, true);

        $this->productFacade->createConcreteProductAttributes(
            $idConcreteProduct,
            $idLocale,
            $name,
            json_encode(
                [
                    'image_url' => '/images/product/robot_buttons_black.png',
                    'weight' => 1.2,
                    'material' => 'aluminium',
                    'gender' => 'b',
                    'age' => 8,
                    'available' => true,
                ]
            )
        );

        return $idConcreteProduct;
    }

    /**
     * @group Exporter
     *
     * @param QueryExpanderPluginInterface[] $expanderCollection
     * @param DataProcessorPluginInterface[] $processors
     * @param array $expectedResult
     */
    public function doExporterTest(array $expanderCollection, array $processors, array $expectedResult)
    {
        $query = $this->prepareQuery();

        foreach ($expanderCollection as $expander) {
            $query = $expander->expandQuery($query, $this->localeName);
        }

        $results = $query->find();

        $processedResultSet = [];
        foreach ($processors as $processor) {
            $processedResultSet = $processor->processData($results, $processedResultSet, $this->localeName);
        }

        $this->assertEquals($expectedResult, $processedResultSet);
    }

    /**
     * @return ModelCriteria
     * @throws PropelException
     */
    protected function prepareQuery()
    {
        $query = SpyTouchQuery::create()
            ->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
            ->setFormatter(new PropelArraySetFormatter())
            ->filterByItemType('test');

        return $query;
    }

    /**
     * @group Exporter
     */
    public function testCategoryExport()
    {
        $this->eraseUrlsAndCategories();

        $this->createAttributeType();

        $idRootCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('RootCategory'),
            $this->idLocale
        );
        $idCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('TestCategory'),
            $this->idLocale
        );
        $idChildCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('ChildCategory'),
            $this->idLocale
        );

        $idRootCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idRootCategory, null, true),
            $this->idLocale
        );
        $idCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idCategory, $idRootCategoryNode),
            $this->idLocale
        );
        $idChildCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idChildCategory, $idCategoryNode),
            $this->idLocale
        );

        $this->touchFacade->touchActive('test', $idCategoryNode);
        $this->touchFacade->touchActive('test', $idRootCategoryNode);
        $this->touchFacade->touchActive('test', $idChildCategoryNode);

        $this->doExporterTest(
            [   //expanders
                $this->locator->categoryExporter()->pluginCategoryNodeQueryExpanderPlugin()
            ],
            [   //processors
                $this->locator->categoryExporter()->pluginCategoryNodeProcessorPlugin()
            ],
            [
                'de.abcde.resource.categorynode.' . $idCategoryNode =>
                    [
                        'node_id' => (string)$idCategoryNode,
                        'children' => [
                            $idChildCategoryNode => [
                                'node_id' => (string)$idChildCategoryNode,
                                'name' => 'ChildCategory',
                                'url' => '/testcategory/childcategory'
                            ]
                        ],
                        'parents' => [
                            $idRootCategoryNode => [
                                'node_id' => (string)$idRootCategoryNode,
                                'name' => 'RootCategory',
                                'url' => '/'
                            ]
                        ],
                        'name' => 'TestCategory',
                        'url' => '/testcategory'
                    ],
                'de.abcde.resource.categorynode.' . $idChildCategoryNode =>
                    [
                        'node_id' => (string)$idChildCategoryNode,
                        'children' => [

                        ],
                        'parents' => [
                            $idCategoryNode => [
                                'node_id' => (string)$idCategoryNode,
                                'name' => 'TestCategory',
                                'url' => '/testcategory'
                            ],
                            $idRootCategoryNode => [
                                'node_id' => (string)$idRootCategoryNode,
                                'name' => 'RootCategory',
                                'url' => '/'
                            ]
                        ],
                        'name' => 'ChildCategory',
                        'url' => '/testcategory/childcategory'
                    ]
            ]
        );
    }

    /**
     * @group Exporter
     */
    public function testProductsWithCategoryNodes()
    {
        $this->eraseUrlsAndCategories();

        $this->createAttributeType();
        $idProduct = $this->createProduct('TestSku', 'TestProductName', $this->idLocale);
        $this->urlFacade->createUrl('/some-url', $this->localeName, 'product', $idProduct);
        $this->touchFacade->touchActive('test', $idProduct);

        $idRootCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('ARootCategory'),
            $this->idLocale
        );

        $idRootCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idRootCategory, null, true),
            $this->idLocale
        );

        $idCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('ACategory'),
            $this->idLocale
        );

        $idCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idCategory, $idRootCategoryNode),
            $this->idLocale
        );

        $this->productCategoryFacade->createProductCategoryMapping('TestSku', 'ACategory', $this->idLocale);

        $this->doExporterTest(
            [
                $this->locator->productFrontendExporterConnector()->pluginProductQueryExpanderPlugin(),
                $this->locator->productCategoryFrontendExporterConnector()->pluginProductCategoryBreadcrumbQueryExpanderPlugin()
            ],
            [
                $this->locator->productFrontendExporterConnector()->pluginProductProcessorPlugin(),
                $this->locator->productCategoryFrontendExporterConnector()->pluginProductCategoryBreadcrumbProcessorPlugin()
            ],
            ['de.abcde.resource.product.' . $idProduct =>
                [
                    'sku' => 'TestSku',
                    'attributes' => [
                        'image_url' => '/images/product/robot_buttons_black.png',
                        'thumbnail_url' => '/images/product/default.png',
                        'price' => 1395,
                        'width' => 12,
                        'height' => 27,
                        'depth' => 850,
                        'main_color' => 'gray',
                        'other_colors' => 'red',
                        'weight' => 1.2,
                        'material' => 'aluminium',
                        'gender' => 'b',
                        'age' => 8,
                        'description' => 'A description!',
                        'name' => 'Ted Technical Robot',
                        'available' => true,
                    ],
                    'name' => 'TestProductName',
                    'url' => '/some-url',
                    'category' => [
                        $idCategoryNode => [
                            'node_id' => (string)$idCategoryNode,
                            'name' => 'ACategory',
                            'url' => '/acategory'
                        ]
                    ]
                ]
            ]
        );
    }

    /**
     * @group Exporter
     */
    public function testNavigationExporter()
    {
        $this->eraseUrlsAndCategories();

        $idToysCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('Toys1'),
            $this->idLocale
        );
        $idToysCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idToysCategory, null, true),
            $this->idLocale
        );

        $idSoftToyCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('Soft Toy1'),
            $this->idLocale
        );
        $idSoftToyCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idSoftToyCategory, $idToysCategoryNode),
            $this->idLocale
        );

        $idRobotCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('Robot1'),
            $this->idLocale
        );
        $idRobotCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idRobotCategory, $idToysCategoryNode),
            $this->idLocale
        );

        $idWindupCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('Wind-Up1'),
            $this->idLocale
        );
        $idWindupCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idWindupCategory, $idRobotCategoryNode),
            $this->idLocale
        );

        $idNoWindupCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('No Wind-up1'),
            $this->idLocale
        );
        $idNoWindupCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idNoWindupCategory, $idRobotCategoryNode),
            $this->idLocale
        );

        $idExoticCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('Exotic1'),
            $this->idLocale
        );
        $idExoticCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idExoticCategory, $idSoftToyCategoryNode),
            $this->idLocale
        );

        $idLocalCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('Local1'),
            $this->idLocale
        );
        $idLocalCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idLocalCategory, $idSoftToyCategoryNode),
            $this->idLocale
        );

        $this->touchFacade->touchActive('test', $idToysCategoryNode);

        $expectedResult = [
            'de.abcde.category.navigation' => [
                [
                    "node_id" => (string)$idRobotCategoryNode,
                    "name" => "Robot1",
                    "url" => "/robot1",
                    "children" => [
                        $idWindupCategoryNode => [
                            "node_id" => (string)$idWindupCategoryNode,
                            "url" => "/robot1/wind-up1",
                            "name" => "Wind-Up1"
                        ],
                        $idNoWindupCategoryNode => [
                            "node_id" => (string)$idNoWindupCategoryNode,
                            "url" => "/robot1/no-wind-up1",
                            "name" => "No Wind-up1"
                        ]
                    ],
                    "parents" => [
                        $idToysCategoryNode => [
                            "node_id" => (string)$idToysCategoryNode,
                            "url" => "/",
                            "name" => "Toys1"
                        ]
                    ]
                ],
                [
                    "node_id" => (string)$idSoftToyCategoryNode,
                    "name" => "Soft Toy1",
                    "url" => "/soft-toy1",
                    "children" => [
                        $idExoticCategoryNode => [
                            "node_id" => (string)$idExoticCategoryNode,
                            "url" => "/soft-toy1/exotic1",
                            "name" => "Exotic1"
                        ],
                        $idLocalCategoryNode => [
                            "node_id" => (string)$idLocalCategoryNode,
                            "url" => "/soft-toy1/local1",
                            "name" => "Local1"
                        ]
                    ],
                    "parents" => [
                        $idToysCategoryNode => [
                            "node_id" => (string)$idToysCategoryNode,
                            "url" => "/",
                            "name" => "Toys1"
                        ]
                    ]
                ]
            ]
        ];

        $expander = [$this->locator->categoryExporter()->pluginNavigationQueryExpanderPlugin()];
        $processor = [$this->locator->categoryExporter()->pluginNavigationProcessorPlugin()];
        $this->doExporterTest(
            $expander,
            $processor,
            $expectedResult
        );
    }

    protected function setUp()
    {
        parent::setUp();
        $this->locator = Locator::getInstance();
        $this->localeName = 'ABCDE';
        $this->localeFacade = $this->locator->locale()->facade();
        $this->productFacade = $this->locator->product()->facade();
        $this->categoryFacade = $this->locator->category()->facade();
        $this->touchFacade = $this->locator->touch()->facade();
        $this->urlFacade = $this->locator->url()->facade();
        $this->productCategoryFacade = $this->locator->productCategory()->facade();
        $this->idLocale = $this->localeFacade->createLocale($this->localeName);
    }

    protected function eraseUrlsAndCategories()
    {
        Propel::getConnection()->query('SET foreign_key_checks = 0;');
        SpyUrlQuery::create()->deleteAll();
        SpyCategoryClosureTableQuery::create()->deleteAll();
        SpyCategoryAttributeQuery::create()->deleteAll();
        SpyCategoryNodeQuery::create()->deleteAll();
        SpyCategoryQuery::create()->deleteAll();
        Propel::getConnection()->query('SET foreign_key_checks = 1;');
    }

    /**
     * @param $name
     *
     * @return Category
     */
    protected function createCategoryTransfer($name)
    {
        $categoryTransfer = $this->locator->category()->transferCategory();
        $categoryTransfer->setName($name);

        return $categoryTransfer;
    }

    /**
     * @param int $idCategory
     * @param bool $isRoot
     * @param int $idParentCategory
     *
     * @return CategoryNode
     */
    protected function createCategoryNodeTransfer($idCategory, $idParentCategory, $isRoot = false)
    {
        $categoryNodeTransfer = $this->locator->category()->transferCategoryNode();
        $categoryNodeTransfer->setIsRoot($isRoot);
        $categoryNodeTransfer->setFkCategory($idCategory);
        $categoryNodeTransfer->setFkParentCategoryNode($idParentCategory);

        return $categoryNodeTransfer;
    }
}
