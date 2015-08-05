<?php

namespace SprykerFeature\Zed\Category\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Category\Communication\Table\CategoryAttributeTable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListController extends AbstractController
{

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
        $idCategory = $request->get('id');

        $children = $this->getFacade()
            ->getNodeTreeForJsDisplay(
                $idCategory,
                $this->getDependencyContainer()->getCurrentLocale()
            )
        ;

        return $this->jsonResponse([
            'code' => Response::HTTP_OK,
            'data' => $children,
            'idCategory' => $idCategory,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function attributesAction(Request $request)
    {
        $idCategory = $request->get('id');

        /* @var CategoryAttributeTable $table */
        $table = $this->getDependencyContainer()
            ->createCategoryAttributeTable($idCategory)
        ;

        $tableData['table'] = $table->fetchData();
        $tableData['table']['header'] = $table->getConfiguration()->getHeader();

        return $this->viewResponse($tableData);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function urlsAction(Request $request)
    {
        $idCategory = $request->get('id');

        $table = $this->getDependencyContainer()
            ->createUrlTable($idCategory)
        ;

        $tableData['table'] = $table->fetchData();
        $tableData['table']['header'] = $table->getConfiguration()->getHeader();

        return $this->viewResponse($tableData);
    }

}
