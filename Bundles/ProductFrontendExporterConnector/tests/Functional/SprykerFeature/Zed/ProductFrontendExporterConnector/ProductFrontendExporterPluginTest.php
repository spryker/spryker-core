<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\ProductFrontendExporterConnector;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AbstractProductTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use Pyz\Zed\Locale\Business\LocaleFacade;
use Pyz\Zed\Product\Business\ProductFacade;
use Pyz\Zed\Touch\Business\TouchFacade;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchQuery;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\Library\Propel\Formatter\PropelArraySetFormatter;
use SprykerFeature\Zed\Url\Business\UrlFacade;

/**
 * @group SprykerFeature
 * @group Zed
 * @group ProductFrontendExporterConnector
 * @group ProductFrontendExporterPluginTest
 * @group FrontendExporterPlugin
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
     * @var LocaleTransfer
     */
    protected $locale;

    /**
     * @group Exporter
     */
    public function testSoleProductExporter()
    {
        $this->createAttributeType();
        $idAbstractProduct = $this->createProduct('TestSku', 'TestProductName', $this->locale);
        $this->urlFacade->createUrl('/some-url', $this->locale, 'abstract_product', $idAbstractProduct);
        $this->touchFacade->touchActive('test', $idAbstractProduct);

        $this->doExporterTest(
            [//expanders
                $this->locator->productFrontendExporterConnector()->pluginProductQueryExpanderPlugin(),
            ],
            [//processors
                $this->locator->productFrontendExporterConnector()->pluginProductProcessorPlugin(),
            ],
            [
                'de.abcde.resource.abstract_product.' . $idAbstractProduct => [
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
    protected function createProduct($sku, $name, LocaleTransfer $locale)
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
        $localizedAttributesTransfer = new LocalizedAttributesTransfer();
        $localizedAttributesTransfer->setLocale($locale);
        $localizedAttributesTransfer->setName($name);
        $localizedAttributesTransfer->setAttributes(
            [
                'thumbnail_url' => '/images/product/default.png',
                'main_color' => 'gray',
                'other_colors' => 'red',
                'description' => 'A description!',
                'name' => 'Ted Technical Robot',
            ]
        );
        $abstractProductTransfer->addLocalizedAttributes($localizedAttributesTransfer);

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

    protected function setUp()
    {
        parent::setUp();
        $this->locator = Locator::getInstance();
        $this->localeFacade = $this->locator->locale()->facade();
        $this->productFacade = $this->locator->product()->facade();
        $this->categoryFacade = $this->locator->category()->facade();
        $this->touchFacade = $this->locator->touch()->facade();
        $this->urlFacade = $this->locator->url()->facade();
        $this->locale = $this->localeFacade->createLocale('ABCDE');
    }

}
