<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Shared\Category\CategoryConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @deprecated This controller has been replaced by \Spryker\Zed\Category\Communication\Controller\ReSortController
 *
 * @method \Spryker\Zed\Category\Business\CategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface getRepository()
 */
class NodeController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $idCategoryNode = $request->get(CategoryConstants::PARAM_ID_NODE);

        $locale = $this->getFactory()
            ->getCurrentLocale();

        $nodeList = $this->getQueryContainer()
            ->getCategoryNodesWithOrder($idCategoryNode, $locale->getIdLocale())
            ->find();

        $items = [];
        foreach ($nodeList as $nodeEntity) {
            $items[] = [
                'id' => $nodeEntity->getIdCategoryNode(),
                'text' => $nodeEntity->getCategory()
                    ->getLocalisedAttributes($locale->getIdLocale())
                    ->getFirst()
                    ->getName(),
            ];
        }

        return [
            'items' => $items,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function reorderAction(Request $request)
    {
        $locale = $this->getFactory()
            ->getCurrentLocale();

        $categoryNodesToReorder = (array)json_decode($request->request->get('nodes'), true);

        $order = count($categoryNodesToReorder) - 1;
        foreach ($categoryNodesToReorder as $index => $nodeData) {
            $idNode = $nodeData['id'];

            $nodeEntity = $this->getQueryContainer()
                ->queryNodeById($idNode)
                ->findOne();

            $nodeTransfer = new NodeTransfer();
            $nodeTransfer->fromArray($nodeEntity->toArray());

            $nodeTransfer->setNodeOrder($order);

            $this->getFacade()
                ->updateCategoryNode($nodeTransfer, $locale);

            $order--;
        }

        return $this->jsonResponse([
            'code' => Response::HTTP_OK,
            'message' => 'Category nodes successfully reordered.',
        ]);
    }
}
