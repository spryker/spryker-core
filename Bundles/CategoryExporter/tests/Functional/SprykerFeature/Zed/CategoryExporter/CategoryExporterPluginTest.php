<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\CategoryExporter;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerEngine\Zed\Touch\Business\TouchFacade;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchQuery;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryAttributeQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryClosureTableQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNodeQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryQuery;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\Library\Propel\Formatter\PropelArraySetFormatter;
use SprykerFeature\Zed\Url\Business\UrlFacade;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyUrlQuery;

/**
 * @group SprykerFeature
 * @group Zed
 * @group CategoryExporter
 * @group CategoryExporterPluginTest
 * @group FrontendExporterPlugin
 */
class CategoryExporterPluginTest extends AbstractFunctionalTest
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

        $this->localeFacade = $this->getFacade('SprykerEngine', 'Locale');
        $this->locale = $this->localeFacade->createLocale('ABCDE');

        $this->categoryFacade = $this->getFacade('SprykerFeature', 'Category');

        $this->touchFacade = $this->getFacade('SprykerEngine', 'Touch');
        $this->urlFacade = $this->getFacade('SprykerFeature', 'Url');
    }

    public function testNavigationExporter()
    {
        $this->markTestSkipped('This test was using a mechanism to truncate tables, this is wrong in tests');

        $idToysCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('Toys1'),
            $this->locale
        );
        $idToysCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idToysCategory, null, true),
            $this->locale
        );

        $idSoftToyCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('Soft Toy1'),
            $this->locale
        );
        $idSoftToyCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idSoftToyCategory, $idToysCategoryNode),
            $this->locale
        );

        $idRobotCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('Robot1'),
            $this->locale
        );
        $idRobotCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idRobotCategory, $idToysCategoryNode),
            $this->locale
        );

        $idWindupCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('Wind-Up1'),
            $this->locale
        );
        $idWindupCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idWindupCategory, $idRobotCategoryNode),
            $this->locale
        );

        $idNoWindupCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('No Wind-up1'),
            $this->locale
        );
        $idNoWindupCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idNoWindupCategory, $idRobotCategoryNode),
            $this->locale
        );

        $idExoticCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('Exotic1'),
            $this->locale
        );
        $idExoticCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idExoticCategory, $idSoftToyCategoryNode),
            $this->locale
        );

        $idLocalCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('Local1'),
            $this->locale
        );
        $idLocalCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idLocalCategory, $idSoftToyCategoryNode),
            $this->locale
        );

        $this->touchFacade->touchActive('test', $idToysCategoryNode);

        $expectedResult = [
            'de.abcde.category.navigation' => [
                [
                    'node_id' => (string) $idRobotCategoryNode,
                    'name' => 'Robot1',
                    'url' => '/robot1',
                    'children' => [
                        $idWindupCategoryNode => [
                            'node_id' => (string) $idWindupCategoryNode,
                            'url' => '/robot1/wind-up1',
                            'name' => 'Wind-Up1',
                        ],
                        $idNoWindupCategoryNode => [
                            'node_id' => (string) $idNoWindupCategoryNode,
                            'url' => '/robot1/no-wind-up1',
                            'name' => 'No Wind-up1',
                        ],
                    ],
                    'parents' => [
                        $idToysCategoryNode => [
                            'node_id' => (string) $idToysCategoryNode,
                            'url' => '/',
                            'name' => 'Toys1',
                        ],
                    ],
                    'image' => null,
                ],
                [
                    'node_id' => (string) $idSoftToyCategoryNode,
                    'name' => 'Soft Toy1',
                    'url' => '/soft-toy1',
                    'children' => [
                        $idExoticCategoryNode => [
                            'node_id' => (string) $idExoticCategoryNode,
                            'url' => '/soft-toy1/exotic1',
                            'name' => 'Exotic1',
                        ],
                        $idLocalCategoryNode => [
                            'node_id' => (string) $idLocalCategoryNode,
                            'url' => '/soft-toy1/local1',
                            'name' => 'Local1',
                        ],
                    ],
                    'parents' => [
                        $idToysCategoryNode => [
                            'node_id' => (string) $idToysCategoryNode,
                            'url' => '/',
                            'name' => 'Toys1',
                        ],
                    ],
                    'image' => null,
                ],
            ],
        ];

        $navigationQueryExpanderPlugin = $this->getPluginByClassName('SprykerFeature\Zed\CategoryExporter\Communication\Plugin\NavigationQueryExpanderPlugin');
        $navigationProcessorPlugin = $this->getPluginByClassName('SprykerFeature\Zed\CategoryExporter\Communication\Plugin\NavigationProcessorPlugin');
        $expander = [$navigationQueryExpanderPlugin];
        $processor = [$navigationProcessorPlugin];
        $this->doExporterTest(
            $expander,
            $processor,
            $expectedResult
        );
    }

    public function testCategoryExport()
    {
        $this->markTestSkipped('This test was using a mechanism to truncate tables, this is wrong in tests');

        $idRootCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('RootCategory'),
            $this->locale
        );
        $idCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('TestCategory'),
            $this->locale
        );
        $idChildCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('ChildCategory'),
            $this->locale
        );

        $idRootCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idRootCategory, null, true),
            $this->locale
        );
        $idCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idCategory, $idRootCategoryNode),
            $this->locale
        );
        $idChildCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idChildCategory, $idCategoryNode),
            $this->locale
        );

        $this->touchFacade->touchActive('test', $idCategoryNode);
        $this->touchFacade->touchActive('test', $idRootCategoryNode);
        $this->touchFacade->touchActive('test', $idChildCategoryNode);

        $this->doExporterTest(
            [//expanders
                $this->getPluginByClassName('SprykerFeature\Zed\CategoryExporter\Communication\Plugin\CategoryNodeQueryExpanderPlugin'),
            ],
            [//processors
                $this->getPluginByClassName('SprykerFeature\Zed\CategoryExporter\Communication\Plugin\CategoryNodeProcessorPlugin'),
            ],
            [
                'de.abcde.resource.categorynode.' . $idCategoryNode => [
                        'node_id' => (string) $idCategoryNode,
                        'children' => [
                            $idChildCategoryNode => [
                                'node_id' => (string) $idChildCategoryNode,
                                'name' => 'ChildCategory',
                                'url' => '/testcategory/childcategory',
                            ],
                        ],
                        'parents' => [
                            $idRootCategoryNode => [
                                'node_id' => (string) $idRootCategoryNode,
                                'name' => 'RootCategory',
                                'url' => '/',
                            ],
                        ],
                        'name' => 'TestCategory',
                        'url' => '/testcategory',
                        'image' => null,
                    ],
                'de.abcde.resource.categorynode.' . $idChildCategoryNode => [
                        'node_id' => (string) $idChildCategoryNode,
                        'children' => [

                        ],
                        'parents' => [
                            $idCategoryNode => [
                                'node_id' => (string) $idCategoryNode,
                                'name' => 'TestCategory',
                                'url' => '/testcategory',
                            ],
                            $idRootCategoryNode => [
                                'node_id' => (string) $idRootCategoryNode,
                                'name' => 'RootCategory',
                                'url' => '/',
                            ],
                        ],
                        'name' => 'ChildCategory',
                        'url' => '/testcategory/childcategory',
                        'image' => null,
                    ],
            ]
        );
    }

    /**
     * @param string $name
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
