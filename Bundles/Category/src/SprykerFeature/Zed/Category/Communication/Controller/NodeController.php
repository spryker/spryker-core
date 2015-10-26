<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\NodeTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\CategoryConfig;
use SprykerFeature\Zed\Category\Communication\CategoryDependencyContainer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method CategoryFacade getFacade()
 * @method CategoryDependencyContainer getDependencyContainer()
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

        $locale = $this->getDependencyContainer()
            ->createCurrentLocale();

        $nodeList = $this->getDependencyContainer()
            ->createCategoryQueryContainer()
            ->getCategoryNodesWithOrder($idCategoryNode, $locale->getIdLocale())
            ->find()
        ;

        $items = [];
        foreach ($nodeList as $nodeEntity) {
            $items[] = [
                'id' => $nodeEntity->getIdCategoryNode(),
                'text' => $nodeEntity->getCategory()->getAttributes()->getFirst()->getName(),
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
        $locale = $this->getDependencyContainer()
            ->createCurrentLocale();

        $categoryNodesToReorder = (array) json_decode($request->request->get('nodes'), true);

        $order = count($categoryNodesToReorder) - 1;
        foreach ($categoryNodesToReorder as $index => $nodeData) {
            $idNode = $nodeData['id'];

            $nodeEntity = $this->getDependencyContainer()
                ->createCategoryQueryContainer()
                ->queryNodeById($idNode)
                ->findOne();

            $nodeTransfer = (new NodeTransfer())
                ->fromArray($nodeEntity->toArray())
            ;

            $nodeTransfer->setNodeOrder($order);

            $this->getDependencyContainer()
                ->createCategoryFacade()
                ->updateCategoryNode($nodeTransfer, $locale)
            ;

            $order--;
        }

        return $this->jsonResponse([
                'code' => Response::HTTP_OK,
                'message' => 'Category nodes successfully reordered',
            ]
        );
    }

}
