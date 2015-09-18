<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $items = [];
        for ($i = 1; $i < 10; $i++) {
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
        $categoryNodes = json_decode($request->request->get('nodes'), true);

        return $this->jsonResponse([
                'code' => Response::HTTP_OK,
                'message' => 'Category nodes successfully reordered',
            ]
        );
    }

}
