<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NodeController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        // demo data, this should be from category closure table
        $items = [];
        for ($i=1; $i<10; $i++) {
            $items[] = [
                'id' => $i,
                'text' => 'Item ' . $i,
            ];
        }

        return [
            'items' => $items,
        ];
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function reorderAction(Request $request)
    {
        $categoryNodesItems = json_decode($request->request->get('nodes'), true);

//        The array looks like this. Create entities and save the new order
//        $categoryNodesItems = [
//            ['id' => 2],
//            ['id' => 6],
//            ['id' => 4],
//            ['id' => 1],
//        ];

        return $this->jsonResponse([
                'code' => Response::HTTP_OK, // if error Response::HTTP_BAD_REQUEST
                'message' => 'response translated message',
            ]
        );
    }

}
