<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Controller;

use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @deprecated This class has been replaced by \Spryker\Zed\Category\Communication\Controller\RootController
 *
 * @method \Spryker\Zed\Category\Business\CategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 */
class IndexController extends AbstractController
{
    public const PARAM_ID_CATEGORY_NODE = 'id-category-node';

    /**
     * @return array
     */
    public function indexAction()
    {
        $rootCategories = $this->getFactory()
            ->createRootNodeTable();

        return $this->viewResponse([
            'rootCategories' => $rootCategories->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function rootNodeTableAction()
    {
        $table = $this->getFactory()
            ->createRootNodeTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function nodeAction(Request $request)
    {
        $idCategoryNode = $this->castId($request->get(self::PARAM_ID_CATEGORY_NODE));

        $categories = $this->getCategoryChildrenByIdCategory($idCategoryNode);

        return $this->viewResponse([
            'code' => Response::HTTP_OK,
            'categories' => $categories,
            'idCategoryNode' => $idCategoryNode,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function nodeByNameAction(Request $request)
    {
        $categoryName = $request->request->get('category-name');

        $idCategory = $this->getFacade()->getCategoryNodeIdentifier(
            trim($categoryName),
            $this->getFactory()->getCurrentLocale()
        );

        $children = $this->getCategoryChildrenByIdCategory($idCategory);

        return $this->jsonResponse([
            'code' => Response::HTTP_OK,
            'data' => $children,
            'idCategoryNode' => $idCategory,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function attributesAction(Request $request)
    {
        $idCategory = $this->castId($request->get(self::PARAM_ID_CATEGORY_NODE));

        /** @var \Spryker\Zed\Category\Communication\Table\CategoryAttributeTable $table */
        $table = $this->getFactory()
            ->createCategoryAttributeTable($idCategory);

        $tableData = $this->getTableArrayFormat($table);

        return $this->viewResponse($tableData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function urlsAction(Request $request)
    {
        $idCategory = $this->castId($request->get(self::PARAM_ID_CATEGORY_NODE));

        $table = $this->getFactory()
            ->createUrlTable($idCategory);

        $tableData = $this->getTableArrayFormat($table);

        return $this->viewResponse($tableData);
    }

    /**
     * @return void
     */
    public function rebuildClosureTableAction()
    {
        $this->getFacade()
            ->rebuildClosureTable();

        exit('<br/>Done');
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\AbstractTable $table
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
                $this->getFactory()->getCurrentLocale()
            );
    }
}
