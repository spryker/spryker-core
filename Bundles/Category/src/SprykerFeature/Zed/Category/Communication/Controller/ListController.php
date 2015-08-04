<?php

namespace SprykerFeature\Zed\Category\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListController extends AbstractController
{
    public function indexAction()
    {
        $rootCategories = $this->getDependencyContainer()
            ->createRootNodeTable()
        ;

        return $this->viewResponse([
            'rootCategories' => $rootCategories->render(),
        ]);
    }

    public function rootNodeTableAction()
    {
        $table = $this->getDependencyContainer()
            ->createRootNodeTable()
        ;

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    public function nodeAction(Request $request)
    {
        $idCategory = $request->get('id');
        if ($idCategory == 14) {
            $data = [
                'text' => 'Categ 14',
                'state' => [
                    'opened' => true,
                    'selected' => true
                ],
                'children' => [
                    'Child 1',
                    'Child 2',
                    'Child 3',
                ]
            ];
        } else {
            $data = [
                'text' => 'Categ 19',
                'state' => [
                    'opened' => true,
                    'selected' => true
                ],
                'children' => [
                    'Child 4',
                    'Child 5',
                    'Child 6'
                ]
            ];
        }

        return $this->jsonResponse([
            'code' => Response::HTTP_OK,
            'data' => $data,
            'idCategory' => $idCategory,
        ]);
    }

    public function attributesAction(Request $request)
    {

    }
}
