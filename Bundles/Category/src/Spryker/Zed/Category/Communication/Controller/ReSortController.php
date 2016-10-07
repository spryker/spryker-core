<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Shared\Category\CategoryConstants;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\Category\Business\CategoryFacade getFacade()
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainer getQueryContainer()
 */
class ReSortController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCategoryNode = $request->get(CategoryConstants::PARAM_ID_NODE);
        $localeTransfer = $this->getFactory()->getCurrentLocale();

        $categoryNodeCollection = $this
            ->getQueryContainer()
            ->getCategoryNodesWithOrder($idCategoryNode, $localeTransfer->getIdLocale())
            ->find();

        $items = [];
        foreach ($categoryNodeCollection as $categoryNodeEntity) {
            $items[] = [
                'id' => $categoryNodeEntity->getIdCategoryNode(),
                'text' => $categoryNodeEntity
                    ->getCategory()
                    ->getLocalisedAttributes($localeTransfer->getIdLocale())
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
    public function saveAction(Request $request)
    {
        $localeTransfer = $this->getFactory()->getCurrentLocale();
        $categoryNodesToReorder = (array)json_decode($request->request->get('nodes'), true);

        $positionCursor = count($categoryNodesToReorder) - 1;
        foreach ($categoryNodesToReorder as $index => $nodeData) {
            $idNode = $nodeData['id'];

            $nodeEntity = $this
                ->getQueryContainer()
                ->queryNodeById($idNode)
                ->findOne();

            $nodeTransfer = new NodeTransfer();
            $nodeTransfer->fromArray($nodeEntity->toArray());
            $nodeTransfer->setNodeOrder($positionCursor);

            $this->getFacade()->updateCategoryNode($nodeTransfer, $localeTransfer);

            $positionCursor--;
        }

        return $this->jsonResponse([
            'code' => Response::HTTP_OK,
            'message' => 'Category nodes successfully re-sorted.',
        ]);
    }

}
