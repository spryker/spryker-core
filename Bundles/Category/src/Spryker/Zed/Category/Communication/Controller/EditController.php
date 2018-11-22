<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Category\CategoryConstants;
use Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Category\Business\CategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface getRepository()
 */
class EditController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $this->getFacade()->syncCategoryTemplate();
        $idCategory = $request->query->getInt(CategoryConstants::PARAM_ID_CATEGORY);

        $categoryTransfer = $this->getFacade()->findCategoryById($idCategory);

        if ($categoryTransfer === null) {
            $this->addErrorMessage(sprintf('Category with id %s doesn\'t exist', $request->get('id-category')));

            return $this->redirectResponse($this->getFactory()->getConfig()->getDefaultRedirectUrl());
        }

        $form = $this->getFactory()
            ->createCategoryEditForm($categoryTransfer)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryTransfer = $this->getCategoryTransferFromForm($form);
            try {
                $this->getFacade()->update($categoryTransfer);
                $this->addSuccessMessage('The category was updated successfully.');

                return $this->redirectResponse(
                    $this->createSuccessRedirectUrl($categoryTransfer->getIdCategory())
                );
            } catch (CategoryUrlExistsException $e) {
                $this->addErrorMessage($e->getMessage());
            }
        }

        return $this->viewResponse([
            'categoryForm' => $form->createView(),
            'currentLocale' => $this->getFactory()->getCurrentLocale()->getLocaleName(),
            'idCategory' => $this->castId($request->query->get(CategoryConstants::PARAM_ID_CATEGORY)),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function getCategoryTransferFromForm(FormInterface $form)
    {
        return $form->getData();
    }

    /**
     * @param int $idCategory
     *
     * @return string
     */
    protected function createSuccessRedirectUrl($idCategory)
    {
        $url = Url::generate(
            '/category/edit',
            [
                CategoryConstants::PARAM_ID_CATEGORY => $idCategory,
            ]
        );

        return $url->build();
    }
}
