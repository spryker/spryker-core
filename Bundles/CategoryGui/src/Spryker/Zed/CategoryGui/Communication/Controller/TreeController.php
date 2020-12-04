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

    protected const REDIRECT_URL = '';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $idCategory = $this->castId($request->query->get(static::PARAM_ID_ROOT_NODE));
        $category = $this->findCategory($idCategory);
        if (!$category) {
            $this->redirectResponse($this->getFactory()->getConfig()->getDefaultRedirectUrl());
        }

        $categoryTree = $this->getCategoryTree($request);

        return $this->viewResponse([
            'childNodes' => $this->getCategoryChildNodeCollection($category),
            'categoryTree' => $categoryTree, // @deprecated Use property `childNodes` instead.
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getCategoryTree(Request $request): array
    {
        $idRootNode = $this->castId($request->query->get(static::REQUEST_PARAM_ID_ROOT_NODE));
        $localeTransfer = $this->getFactory()->getLocaleFacade()->getCurrentLocale();

        return $this
            ->getFactory()
            ->getCategoryFacade()
            ->getTreeNodeChildrenByIdCategoryAndLocale($idRootNode, $localeTransfer);
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    protected function findCategory(int $idCategory): ?CategoryTransfer
    {
        $criteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($idCategory)
            ->setLocaleName($this->getFactory()->getCurrentLocale()->getLocaleName())
            ->setWithChildrenRecursively(true);

        return $this->getFacade()->findCategory($criteriaTransfer);
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
