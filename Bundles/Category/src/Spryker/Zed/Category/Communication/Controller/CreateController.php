<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Category\Business\CategoryFacade getFacade()
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainer getQueryContainer()
 */
class CreateController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $form = $this->getFactory()->createCategoryCreateForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $categoryTransfer = $this->getCategoryTransferFromForm($form);
            try {
                $this->getFacade()->createCategory($categoryTransfer);
                $this->getFacade()->createCategoryNode($categoryTransfer->getCategoryNode());

                $this->addSuccessMessage('The category was added successfully.');

                return $this->redirectResponse('/category/edit?id-category=' . $categoryTransfer->getIdCategory());
            } catch (CategoryUrlExistsException $e) {
                $this->addErrorMessage($e->getMessage());
            }
        }

        return $this->viewResponse([
            'categoryForm' => $form->createView(),
            'currentLocale' => $this->getFactory()->getCurrentLocale()->getLocaleName(),
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

}
