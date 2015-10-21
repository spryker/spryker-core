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
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNode;
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

        $nodes = $this->getDependencyContainer()
            ->createCategoryFacade()
            ->getCategoryNodesWithOrder($idCategoryNode, $locale)
        ;

        $items = [];
        foreach ($nodes as $node) {
            /*
             * @var SpyCategoryNode $node
             */

            $items[] = [
                'id' => $node->getIdCategoryNode(),
                'text' => $node->getCategory()->getAttributes()->getFirst()->getName(),
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

            $node = $this->getDependencyContainer()
                ->createCategoryFacade()
                ->getNodeById($idNode);

            $nodeTransfer = (new NodeTransfer())
                ->fromArray($node->toArray())
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
