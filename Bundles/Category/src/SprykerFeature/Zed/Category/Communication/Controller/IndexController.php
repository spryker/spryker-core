<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\Communication\CategoryDependencyContainer;
use SprykerFeature\Zed\Category\Communication\Table\CategoryAttributeTable;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method CategoryFacade getFacade()
 * @method CategoryDependencyContainer getDependencyContainer()
 * @method CategoryQueryContainer getQueryContainer()
 */
class IndexController extends AbstractController
{

    const PARAM_ID_CATEGORY_NODE = 'id-category-node';

    /**
     * @return array
     */
    public function indexAction()
    {
        $rootCategories = $this->getDependencyContainer()
            ->createRootNodeTable()
        ;

        return $this->viewResponse([
            'rootCategories' => $rootCategories->render(),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function rootNodeTableAction()
    {
        $table = $this->getDependencyContainer()
            ->createRootNodeTable()
        ;

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function nodeAction(Request $request)
    {
        $idCategoryNode = $request->get(self::PARAM_ID_CATEGORY_NODE);

        $categories = $this->getCategoryChildrenByIdCategory($idCategoryNode);

        return $this->viewResponse([
            'code' => Response::HTTP_OK,
            'categories' => $categories,
            'idCategoryNode' => $idCategoryNode,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function nodeByNameAction(Request $request)
    {
        $categoryName = $request->request->get('category-name');

        $idCategory = $this->getFacade()->getCategoryNodeIdentifier(
            trim($categoryName),
            $this->getDependencyContainer()->createCurrentLocale()
        );

        $children = $this->getCategoryChildrenByIdCategory($idCategory);

        return $this->jsonResponse([
            'code' => Response::HTTP_OK,
            'data' => $children,
            'idCategoryNode' => $idCategory,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function attributesAction(Request $request)
    {
        $idCategory = $request->get(self::PARAM_ID_CATEGORY_NODE);

        /* @var CategoryAttributeTable $table */
        $table = $this->getDependencyContainer()
            ->createCategoryAttributeTable($idCategory)
        ;

        $tableData = $this->getTableArrayFormat($table);

        return $this->viewResponse($tableData);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function urlsAction(Request $request)
    {
        $idCategory = $request->get(self::PARAM_ID_CATEGORY_NODE);

        $table = $this->getDependencyContainer()
            ->createUrlTable($idCategory)
        ;

        $tableData = $this->getTableArrayFormat($table);

        return $this->viewResponse($tableData);
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function rebuildClosureTableAction(Request $request)
    {
        $this->getFacade()
            ->rebuildClosureTable()
        ;

        die("<br/>Done");
    }

    /**
     * @param AbstractTable $table
     *
     * @return array
     */
    protected function getTableArrayFormat(AbstractTable $table)
    {
        $tableData = [
            'table' => $table->fetchData(),
        ];
        $tableData['table']['header'] = $table->getConfiguration()->getHeader();

        return $tableData;
    }

    /**
     * @param int $idCategory
     *
     * @return array
     */
    protected function getCategoryChildrenByIdCategory($idCategory)
    {
        return $this->getFacade()
            ->getTreeNodeChildrenByIdCategoryAndLocale(
                $idCategory,
                $this->getDependencyContainer()->createCurrentLocale()
            )
        ;
    }

}
