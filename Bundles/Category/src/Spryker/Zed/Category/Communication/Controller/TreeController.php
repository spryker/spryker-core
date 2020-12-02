<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Category\Business\CategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface getRepository()
 */
class TreeController extends AbstractController
{
    public const PARAM_ID_ROOT_NODE = 'id-root-node';

    protected const REDIRECT_URL = '';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
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
    protected function getCategoryTree(Request $request)
    {
        $idRootNode = $this->castId($request->query->get(self::PARAM_ID_ROOT_NODE));
        $localeTransfer = $this->getFactory()->getCurrentLocale();

        return $this
            ->getFacade()
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
