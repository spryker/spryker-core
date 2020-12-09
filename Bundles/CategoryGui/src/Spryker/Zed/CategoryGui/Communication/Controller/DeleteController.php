<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Controller;

use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 */
class DeleteController extends AbstractController
{
    protected const REQUEST_PARAM_ID_CATEGORY = 'id-category';

    /**
     * @uses \Spryker\Zed\CategoryGui\Communication\Controller\ListController::indexAction()
     */
    protected const ROUTE_CATEGORY_LIST = '/category-gui/list';

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer|null
     */
    protected $currentLocale;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCategory = $this->castId($request->get(static::REQUEST_PARAM_ID_CATEGORY));
        $categoryTransfer = $this->findCategory($idCategory);

        if (!$categoryTransfer) {
            return $this->redirectResponse(static::ROUTE_CATEGORY_LIST);
        }

        $form = $this->getFactory()->createCategoryDeleteForm($idCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->getFactory()
                ->getCategoryFacade()
                ->delete($data['fk_category']);

            return $this->redirectResponse(static::ROUTE_CATEGORY_LIST);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'category' => $categoryTransfer,
            'urls' => $this->getUrls($categoryTransfer),
            'relations' => $this->getRelations($categoryTransfer),
            'parentCategory' => $this->findParentCategory($categoryTransfer),
            'childNodes' => $this->getCategoryChildNodeCollection($categoryTransfer),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    protected function findParentCategory(CategoryTransfer $categoryTransfer): ?CategoryTransfer
    {
        if (!$categoryTransfer->getParentCategoryNode()) {
            return null;
        }

        return $this->findCategory($categoryTransfer->getParentCategoryNode()->getFkCategory());
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    protected function findCategory(int $idCategory): ?CategoryTransfer
    {
        $localeTransfer = $this->getCurrentLocale();

        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($idCategory)
            ->setLocaleName($localeTransfer->getLocaleName())
            ->setWithChildrenRecursively(true);

        $categoryTransfer = $this
            ->getFactory()
            ->getCategoryFacade()
            ->findCategory($categoryCriteriaTransfer);

        if (!$categoryTransfer) {
            return null;
        }

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[]
     */
    protected function getUrls(CategoryTransfer $categoryTransfer): array
    {
        $categoryNodeIds = [];

        foreach ($categoryTransfer->getNodeCollection()->getNodes() as $nodeTransfer) {
            $categoryNodeIds[] = $nodeTransfer->getIdCategoryNode();
        }

        return $this->getRepository()->getCategoryNodeUrls($categoryNodeIds);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return array
     */
    protected function getRelations(CategoryTransfer $categoryTransfer): array
    {
        $relations = [];
        $localeTransfer = $this->getCurrentLocale();
        $categoryRelationReadPlugins = $this->getFactory()->getCategoryRelationReadPlugins();

        foreach ($categoryRelationReadPlugins as $categoryRelationReadPlugin) {
            $relations[] = [
                'name' => $categoryRelationReadPlugin->getRelationName(),
                'list' => $categoryRelationReadPlugin->getRelations($categoryTransfer, $localeTransfer),
            ];
        }

        return $relations;
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

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale(): LocaleTransfer
    {
        if (!$this->currentLocale) {
            $this->currentLocale = $this->getFactory()->getLocaleFacade()->getCurrentLocale();
        }

        return $this->currentLocale;
    }
}
