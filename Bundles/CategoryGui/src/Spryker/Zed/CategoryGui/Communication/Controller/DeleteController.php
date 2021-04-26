<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeUrlCriteriaTransfer;
use Generated\Shared\Transfer\CategoryResponseTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Spryker\Zed\CategoryGui\Communication\Form\DeleteType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 */
class DeleteController extends CategoryAbstractController
{
    protected const REQUEST_PARAM_ID_CATEGORY = 'id-category';

    /**
     * @uses \Spryker\Zed\CategoryGui\Communication\Controller\ListController::indexAction()
     */
    protected const ROUTE_CATEGORY_LIST = '/category-gui/list';
    protected const ROUTE_DELETE_CATEGORY = '/category-gui/delete';

    protected const ERROR_MESSAGE_STORE_RELATION_NOT_REMOVABLE = 'Category with store relation cannot be removed.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCategory = $this->castId($request->get(static::REQUEST_PARAM_ID_CATEGORY));
        $categoryFinder = $this->getFactory()->createCategoryFinder();

        $categoryTransfer = $categoryFinder->findCategoryByIdCategoryAndLocale($idCategory, $this->getCurrentLocale());
        if ($categoryTransfer === null) {
            return $this->redirectResponse(static::ROUTE_CATEGORY_LIST);
        }

        $storeRelationTransfer = $categoryTransfer->getStoreRelation();
        if ($storeRelationTransfer !== null && $storeRelationTransfer->getStores()->count()) {
            return $this->handleCategoryWithStoreRelationError();
        }

        $form = $this->getFactory()->createCategoryDeleteForm($idCategory);
        $form->handleRequest($request);

        $categoryResponseTransfer = $this->handleCategoryDeleteForm($form);
        if ($categoryResponseTransfer->getIsSuccessful()) {
            return $this->redirectResponse(static::ROUTE_CATEGORY_LIST);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'category' => $categoryTransfer,
            'urls' => $this->getUrls($categoryTransfer),
            'relations' => $this->getRelations($categoryTransfer),
            'parentCategory' => $categoryFinder->findParentCategory($categoryTransfer, $this->getCurrentLocale()),
            'childNodes' => $this->getCategoryChildNodeCollection($categoryTransfer),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[]
     */
    protected function getUrls(CategoryTransfer $categoryTransfer): array
    {
        $categoryNodeIds = [];

        foreach ($categoryTransfer->getNodeCollectionOrFail()->getNodes() as $nodeTransfer) {
            $categoryNodeIds[] = $nodeTransfer->getIdCategoryNodeOrFail();
        }

        $categoryNodeUrlCriteriaTransfer = (new CategoryNodeUrlCriteriaTransfer())
            ->setCategoryNodeIds($categoryNodeIds);

        return $this->getFactory()->getCategoryFacade()->getCategoryNodeUrls($categoryNodeUrlCriteriaTransfer);
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleCategoryWithStoreRelationError(): RedirectResponse
    {
        $errorMessageTransfer = (new MessageTransfer())->setValue(static::ERROR_MESSAGE_STORE_RELATION_NOT_REMOVABLE);
        $this->addErrorMessages(new ArrayObject([$errorMessageTransfer]));

        return $this->redirectResponse(static::ROUTE_CATEGORY_LIST);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\CategoryResponseTransfer
     */
    protected function handleCategoryDeleteForm(FormInterface $form): CategoryResponseTransfer
    {
        if (!$form->isSubmitted() || !$form->isValid()) {
            return (new CategoryResponseTransfer())
                ->setIsSuccessful(false);
        }

        $idCategory = $form->getData()[DeleteType::FIELD_FK_NODE_CATEGORY];
        $categoryResponseTransfer = $this->getFactory()
            ->createCategoryDeleteFormHandler()
            ->deleteCategory($idCategory);

        if ($categoryResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessages($categoryResponseTransfer->getMessages());

            return $categoryResponseTransfer;
        }

        $this->addErrorMessages($categoryResponseTransfer->getMessages());

        return $categoryResponseTransfer;
    }
}
