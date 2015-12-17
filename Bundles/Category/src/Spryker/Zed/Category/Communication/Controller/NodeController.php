<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Category\Business\CategoryFacade;
use Spryker\Zed\Category\CategoryConfig;
use Spryker\Zed\Category\Communication\CategoryCommunicationFactory;
use Spryker\Zed\Category\Persistence\CategoryQueryContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method CategoryFacade getFacade()
 * @method CategoryCommunicationFactory getFactory()
 * @method CategoryQueryContainer getQueryContainer()
 */
class NodeController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $idCategoryNode = $request->get(CategoryConfig::PARAM_ID_NODE);

        $locale = $this->getFactory()
            ->createCurrentLocale();

        $nodeList = $this->getFactory()
            ->createCategoryQueryContainer()
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
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function reorderAction(Request $request)
    {
        $locale = $this->getFactory()
            ->createCurrentLocale();

        $categoryNodesToReorder = (array) json_decode($request->request->get('nodes'), true);

        $order = count($categoryNodesToReorder) - 1;
        foreach ($categoryNodesToReorder as $index => $nodeData) {
            $idNode = $nodeData['id'];

            $nodeEntity = $this->getFactory()
                ->createCategoryQueryContainer()
                ->queryNodeById($idNode)
                ->findOne();

            $nodeTransfer = new NodeTransfer();
            $nodeTransfer->fromArray($nodeEntity->toArray());

            $nodeTransfer->setNodeOrder($order);

            $this->getFactory()
                ->createCategoryFacade()
                ->updateCategoryNode($nodeTransfer, $locale);

            $order--;
        }

        return $this->jsonResponse([
                'code' => Response::HTTP_OK,
                'message' => 'Category nodes successfully reordered',
            ]
        );
    }

}
