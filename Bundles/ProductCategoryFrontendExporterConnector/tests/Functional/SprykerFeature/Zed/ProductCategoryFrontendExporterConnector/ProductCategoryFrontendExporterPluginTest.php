<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\ProductCategoryFrontendExporterConnector;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AbstractProductTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerEngine\Zed\Touch\Business\TouchFacade;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchQuery;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\CategoryDependencyProvider;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryAttributeQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryClosureTableQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNodeQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryQuery;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\Library\Propel\Formatter\PropelArraySetFormatter;
use SprykerFeature\Zed\Product\Business\ProductFacade;
use SprykerFeature\Zed\ProductCategory\Business\ProductCategoryFacade;
use SprykerFeature\Zed\Url\Business\UrlFacade;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyUrlQuery;

/**
 * @group SprykerFeature
 * @group Zed
 * @group ProductCategoryFrontendExporterConnector
 * @group ProductCategoryFrontendExporterPluginTest
 * @group FrontendExporterPlugin
 */
class ProductCategoryFrontendExporterPluginTest extends Test
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
     * @var LocaleTransfer
     */
    protected $locale;

    /**
     * @var CategoryFacade
     */
    protected $categoryFacade;

    /**
     * @var ProductFacade
     */
    protected $productFacade;

    /**
     * @var ProductCategoryFacade
     */
    protected $productCategoryFacade;

    /**
     * @var TouchFacade
     */
    protected $touchFacade;

    /**
     * @var UrlFacade
     */
    protected $urlFacade;

    protected function setUp()
    {
        parent::setUp();
        $this->locator = Locator::getInstance();

        $this->localeFacade = $this->locator->locale()->facade();
        $this->locale = $this->localeFacade->createLocale('ABCDE');

        $container = new Container();
        $this->categoryFacade = $this->locator->category()->facade();
        $dependencyProvider = new CategoryDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $this->categoryFacade->setExternalDependencies($container);

        $this->productFacade = $this->locator->product()->facade();
        $this->productCategoryFacade = $this->locator->productCategory()->facade();

        $this->touchFacade = $this->locator->touch()->facade();
        $this->urlFacade = $this->locator->url()->facade();
    }

    public function testProductsWithCategoryNodes()
    {
        $this->markTestSkipped('This test was using a mechanism to truncate tables, this is wrong in tests');

        $this->createAttributeType();
        $idAbstractProduct = $this->createAbstractProductWithVariant('TestSku', 'TestProductName', $this->locale);
        $this->urlFacade->createUrl('/some-url', $this->locale, 'abstract_product', $idAbstractProduct);
        $this->touchFacade->touchActive('test', $idAbstractProduct);

        $idRootCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('ARootCategory'),
            $this->locale
        );

        $idRootCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idRootCategory, null, true),
            $this->locale
        );

        $idCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('ACategory'),
            $this->locale
        );

        $idCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idCategory, $idRootCategoryNode),
            $this->locale
        );

        $this->productCategoryFacade->createProductCategoryMapping('AbstractTestSku', 'ACategory', $this->locale);

        $this->doExporterTest(
            [
                $this->locator->productFrontendExporterConnector()->pluginProductQueryExpanderPlugin(),
                $this->locator->productCategoryFrontendExporterConnector()->pluginProductCategoryBreadcrumbQueryExpanderPlugin(),
            ],
            [
                $this->locator->productFrontendExporterConnector()->pluginProductProcessorPlugin(),
                $this->locator->productCategoryFrontendExporterConnector()->pluginProductCategoryBreadcrumbProcessorPlugin(),
            ],
            ['de.abcde.resource.abstract_product.' . $idAbstractProduct => [
                    'abstract_attributes' => [
                        'thumbnail_url' => '/images/product/default.png',
                        'price' => 1395,
                        'width' => 12,
                        'height' => 27,
                        'depth' => 850,
                        'main_color' => 'gray',
                        'other_colors' => 'red',
                        'description' => 'A description!',
                        'name' => 'Ted Technical Robot',
                    ],
                    'abstract_name' => 'AbstractTestProductName',
                    'abstract_sku' => 'AbstractTestSku',
                    'url' => '/some-url',
                    'concrete_products' => [
                        [
                            'name' => 'TestProductName',
                            'sku' => 'TestSku',
                            'attributes' => [
                                'image_url' => '/images/product/robot_buttons_black.png',
                                'weight' => 1.2,
                                'material' => 'aluminium',
                                'gender' => 'b',
                                'age' => 8,
                                'available' => true,
                            ],
                        ],
                    ],
                    'category' => [
                        $idCategoryNode => [
                            'node_id' => (string) $idCategoryNode,
                            'name' => 'ACategory',
                            'url' => '/acategory',
                        ],
                    ],
                ],
            ]
        );
    }

    protected function createAttributeType()
    {
        if (!$this->productFacade->hasAttributeType('test')) {
            $this->productFacade->createAttributeType('test', 'test');
        }
    }

    /**
     * @param string $sku
     * @param string $name
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    protected function createAbstractProductWithVariant($sku, $name, LocaleTransfer $locale)
    {
        $idAbstractProduct = $this->createAbstractProductWithAttributes('Abstract' . $sku, 'Abstract' . $name, $locale);
        $this->createConcreteProductWithAttributes($idAbstractProduct, $sku, $name, $locale);

        return $idAbstractProduct;
    }

    /**
     * @param string $sku
     * @param string $name
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    protected function createAbstractProductWithAttributes($sku, $name, $locale)
    {
        $abstractProductTransfer = new AbstractProductTransfer();
        $abstractProductTransfer->setSku($sku);

        $abstractProductTransfer->setIsActive(true);
        $abstractProductTransfer->setAttributes(
            [
                'price' => 1395,
                'width' => 12,
                'height' => 27,
                'depth' => 850,
            ]
        );
        $localizedAttributes = new LocalizedAttributesTransfer();
        $localizedAttributes->setLocale($locale);
        $localizedAttributes->setName($name);
        $localizedAttributes->setAttributes([
            'thumbnail_url' => '/images/product/default.png',
            'main_color' => 'gray',
            'other_colors' => 'red',
            'description' => 'A description!',
            'name' => 'Ted Technical Robot',
        ]);

        $abstractProductTransfer->addLocalizedAttributes($localizedAttributes);
        $idAbstractProduct = $this->productFacade->createAbstractProduct($abstractProductTransfer);

        $abstractProductTransfer->setIdAbstractProduct($idAbstractProduct);

        return $idAbstractProduct;
    }

    /**
     * @param int $idAbstractProduct
     * @param string $sku
     * @param string $name
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    protected function createConcreteProductWithAttributes($idAbstractProduct, $sku, $name, LocaleTransfer $locale)
    {
        $concreteProductTransfer = new ConcreteProductTransfer();
        $concreteProductTransfer->setSku($sku);
        $concreteProductTransfer->setIsActive(true);
        $concreteProductTransfer->setAttributes(
            [
                'weight' => 1.2,
                'age' => 8,
                'available' => true,
            ]
        );

        $localizedAttributes = new LocalizedAttributesTransfer();
        $localizedAttributes->setLocale($locale);
        $localizedAttributes->setName($name);
        $localizedAttributes->setAttributes([
            'image_url' => '/images/product/robot_buttons_black.png',
            'material' => 'aluminium',
            'gender' => 'b',
        ]);
        $concreteProductTransfer->addLocalizedAttributes($localizedAttributes);
        $idConcreteProduct = $this->productFacade->createConcreteProduct($concreteProductTransfer, $idAbstractProduct);

        $concreteProductTransfer->setIdConcreteProduct($idConcreteProduct);

        return $idConcreteProduct;
    }

    /**
     * @param $name
     *
     * @return CategoryTransfer
     */
    protected function createCategoryTransfer($name)
    {
        $categoryTransfer = new CategoryTransfer();
        $categoryTransfer->setName($name);

        return $categoryTransfer;
    }

    /**
     * @param int $idCategory
     * @param bool $isRoot
     * @param int $idParentCategory
     *
     * @return NodeTransfer
     */
    protected function createCategoryNodeTransfer($idCategory, $idParentCategory, $isRoot = false)
    {
        $categoryNodeTransfer = new NodeTransfer();
        $categoryNodeTransfer->setIsRoot($isRoot);
        $categoryNodeTransfer->setFkCategory($idCategory);
        $categoryNodeTransfer->setFkParentCategoryNode($idParentCategory);

        return $categoryNodeTransfer;
    }

    /**
     * @param QueryExpanderPluginInterface[] $expanderCollection
     * @param DataProcessorPluginInterface[] $processors
     * @param array $expectedResult
     */
    public function doExporterTest(array $expanderCollection, array $processors, array $expectedResult)
    {
        $query = $this->prepareQuery();

        foreach ($expanderCollection as $expander) {
            $query = $expander->expandQuery($query, $this->locale);
        }

        $results = $query->find();

        $processedResultSet = [];
        foreach ($processors as $processor) {
            $processedResultSet = $processor->processData($results, $processedResultSet, $this->locale);
        }

        $this->assertEquals($expectedResult, $processedResultSet);
    }

    /**
     * @throws PropelException
     *
     * @return ModelCriteria
     */
    protected function prepareQuery()
    {
        $query = SpyTouchQuery::create()
            ->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
            ->setFormatter(new PropelArraySetFormatter())
            ->filterByItemType('test');

        return $query;
    }

}
