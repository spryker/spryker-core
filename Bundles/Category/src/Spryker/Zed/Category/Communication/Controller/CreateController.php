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
class CreateController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $this->getFacade()->syncCategoryTemplate();

        $idParentNode = $this->readParentNodeId($request);
        $form = $this->getFactory()->createCategoryCreateForm($idParentNode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryTransfer = $this->getCategoryTransferFromForm($form);
            try {
                $this->getFacade()->create($categoryTransfer);
                $this->addSuccessMessage('The category was added successfully.');

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
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int|null
     */
    protected function readParentNodeId(Request $request)
    {
        $parentNodeId = $request->query->get(CategoryConstants::PARAM_ID_PARENT_NODE);

        if (!$parentNodeId) {
            return null;
        }

        return $this->castId($parentNodeId);
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
