<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\Communication\CategoryDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CategoryDependencyContainer getDependencyContainer()
 * @method CategoryFacade getFacade()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $rootNodeTable = $this->getDependencyContainer()
            ->createRootNodeTable()
        ;
        $rootNodeTable->init();

        $categoryAttributeTable = $this->getDependencyContainer()
            ->createCategoryAttributeTable(0)
        ;
        $categoryAttributeTable->init();

        $urlTable = $this->getDependencyContainer()
            ->createUrlTable(0)
        ;
        $urlTable->init();

        return $this->viewResponse([
            'rootNodeTable' => $rootNodeTable,
            'categoryAttributeTable' => $categoryAttributeTable,
            'categoryUrlTable' => $urlTable,
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
        $table->init();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function categoryAttributeTableAction(Request $request)
    {
        $table = $this->getDependencyContainer()
            ->createCategoryAttributeTable($request->get('id'))
        ;
        $table->init();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function urlTableAction(Request $request)
    {
        $table = $this->getDependencyContainer()
            ->createUrlTable($request->get('id'));
        $table->init();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return int
     */
    public function addCategoryAction(Request $request)
    {
        $name = $request->get('name');
        $idParent = $request->get('idParent');
        $categoryTransfer = new CategoryTransfer();
        $categoryTransfer->setName($name);
        $idCategory = $this->getFacade()
            ->createCategory(
                $categoryTransfer,
                $this->getDependencyContainer()
                    ->getCurrentLocale()
            );
        $nodeTransfer = new NodeTransfer();
        $nodeTransfer->setFkCategory($idCategory);
        $nodeTransfer->setFkParentCategoryNode($idParent);
        $this->getFacade()
            ->createCategoryNode(
                $nodeTransfer,
                $this->getDependencyContainer()
                    ->getCurrentLocale()
            );

        return $idCategory;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function deleteCategoryAction(Request $request)
    {
        return $this->getFacade()
            ->deleteCategoryByNodeId(
                $request->get('id'),
                $this->getDependencyContainer()
                    ->getCurrentLocale()
            );
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getChildrenAction(Request $request)
    {
        return $this->jsonResponse(
            $this->getFacade()
                ->getChildren(
                    $request->get('id'),
                    $this->getDependencyContainer()
                        ->getCurrentLocale()
                )
        );
    }


    public function getTreeNodesAction(Request $request)
    {
        $tree = $this->getFacade()
            ->getTree(
                $request->get('id_category'),
                $this->getDependencyContainer()
                ->getCurrentLocale()
            );

        return $this->jsonResponse($tree);
    }

    public function getCategoryAttributesAction(Request $request)
    {
        $attributes = [
            [
                "test" => 234
            ]
        ];

        return $this->jsonResponse($attributes);
    }

    /**
     * @return JsonResponse
     */
    public function renderAction()
    {
        $categoryFacade = $this->getFacade();

        return $this->streamedResponse(
            function () use ($categoryFacade) {
                echo $categoryFacade->renderCategoryTreeVisual();
            }
        );
    }
}
