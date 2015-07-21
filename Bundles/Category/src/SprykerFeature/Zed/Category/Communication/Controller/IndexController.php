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
        $idCategory = $this->getFacade()->createCategory(
            $categoryTransfer,
            $this->getDependencyContainer()->getCurrentLocale()
        );
        $nodeTransfer = new NodeTransfer();
        $nodeTransfer->setFkCategory($idCategory);
        $nodeTransfer->setFkParentCategoryNode($idParent);
        $this->getFacade()->createCategoryNode(
            $nodeTransfer,
            $this->getDependencyContainer()->getCurrentLocale()
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
        return $this->getFacade()->deleteCategoryByNodeId(
            $request->get('id'),
            $this->getDependencyContainer()->getCurrentLocale()
        );
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getChildrenAction(Request $request)
    {
        return $this->jsonResponse($this->getFacade()->getChildren(
            $request->get('id'),
            $this->getDependencyContainer()->getCurrentLocale()
        ));
    }

    /**
     * @return JsonResponse
     */
    public function getTreeNodesAction()
    {
        $tree = $this->getFacade()->getTreeAsArray($this->getDependencyContainer()->getCurrentLocale());

        return $this->jsonResponse($tree);
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
