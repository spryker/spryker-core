<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Controller;

use Generated\Shared\Transfer\CategoryResponseTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface getRepository()
 */
class EditController extends CategoryAbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_ID_CATEGORY = 'id-category';

    /**
     * @uses \Spryker\Zed\CategoryGui\Communication\Controller\ListController::indexAction()
     * @var string
     */
    protected const ROUTE_CATEGORY_LIST = '/category-gui/list';
    /**
     * @var string
     */
    protected const ROUTE_CATEGORY_EDIT = '/category-gui/edit';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CATEGORY_DOES_NOT_EXIST = 'Category with id %s does not exist';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idCategory = $this->castId($request->get(static::REQUEST_PARAM_ID_CATEGORY));
        $categoryTransfer = $this->getFactory()
            ->createCategoryEditDataProvider()
            ->getData($idCategory);

        if ($categoryTransfer === null) {
            $this->addErrorMessage(static::ERROR_MESSAGE_CATEGORY_DOES_NOT_EXIST, [
                '%s' => $idCategory,
            ]);

            return $this->redirectResponse(static::ROUTE_CATEGORY_LIST);
        }

        $form = $this->getForm($categoryTransfer);
        $form->handleRequest($request);

        $categoryResponseTransfer = $this->handleCategoryEditForm($form);
        if ($categoryResponseTransfer->getIsSuccessful()) {
            return $this->redirectResponse(
                $this->createSuccessRedirectUrl($idCategory)
            );
        }

        return $this->viewResponse([
            'categoryForm' => $form->createView(),
            'currentLocale' => $this->getCurrentLocale()->getLocaleName(),
            'idCategory' => $idCategory,
            'categoryFormTabs' => $this->getFactory()->createCategoryFormTabs()->createView(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getForm(CategoryTransfer $categoryTransfer): FormInterface
    {
        if ($categoryTransfer->getCategoryNodeOrFail()->getIsRoot()) {
            return $this->getFactory()->createRootCategoryEditForm($categoryTransfer);
        }

        return $this->getFactory()->createCategoryEditForm($categoryTransfer);
    }

    /**
     * @param int $idCategory
     *
     * @return string
     */
    protected function createSuccessRedirectUrl(int $idCategory): string
    {
        $url = Url::generate(
            static::ROUTE_CATEGORY_EDIT,
            [
                static::REQUEST_PARAM_ID_CATEGORY => $idCategory,
            ]
        );

        return $url->build();
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\CategoryResponseTransfer
     */
    protected function handleCategoryEditForm(FormInterface $form): CategoryResponseTransfer
    {
        if (!$form->isSubmitted() || !$form->isValid()) {
            return (new CategoryResponseTransfer())
                ->setIsSuccessful(false);
        }

        $categoryResponseTransfer = $this->getFactory()
            ->createCategoryUpdater()
            ->updateCategory($form->getData());

        if ($categoryResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessages($categoryResponseTransfer->getMessages());

            return $categoryResponseTransfer;
        }

        $this->addErrorMessages($categoryResponseTransfer->getMessages());

        return $categoryResponseTransfer;
    }
}
