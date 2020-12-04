<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Controller;

use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 */
class TreeController extends AbstractController
{
    protected const REQUEST_PARAM_ID_ROOT_NODE = 'id-root-node';

    /**
     * @uses \Spryker\Zed\CategoryGui\Communication\Controller\ListController::indexAction()
     */
    protected const ROUTE_CATEGORY_LIST = '/category-gui/list';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $idCategory = $this->castId($request->query->get(static::REQUEST_PARAM_ID_ROOT_NODE));
        $category = $this->findCategory($idCategory);

        if (!$category) {
            $this->redirectResponse(static::ROUTE_CATEGORY_LIST);
        }

        return $this->viewResponse([
            'childNodes' => $this->getCategoryChildNodeCollection($category),
        ]);
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    protected function findCategory(int $idCategory): ?CategoryTransfer
    {
        $localeTransfer = $this->getFactory()->getLocaleFacade()->getCurrentLocale();

        $criteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($idCategory)
            ->setLocaleName($localeTransfer->getLocaleName())
            ->setWithChildrenRecursively(true);

        return $this->getFactory()->getCategoryFacade()->findCategory($criteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    protected function getCategoryChildNodeCollection(CategoryTransfer $categoryTransfer): NodeCollectionTransfer
    {
        $categoryNodeCollectionTransfer = $categoryTransfer->getNodeCollection();
        if (!$categoryNodeCollectionTransfer || $categoryNodeCollectionTransfer->getNodes()->count() === 0) {
            return new NodeCollectionTransfer();
        }

        return $categoryNodeCollectionTransfer->getNodes()->offsetGet(0)->getChildrenNodes() ?? new NodeCollectionTransfer();
    }
}
