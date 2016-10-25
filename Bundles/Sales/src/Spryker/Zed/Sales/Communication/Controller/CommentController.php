<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\CommentTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\SalesConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\Sales\Business\SalesFacade getFacade()
 */
class CommentController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addAction(Request $request)
    {
        $idSalesOrder = $request->query->get(SalesConfig::PARAM_ID_SALES_ORDER);

        $formDataProvider = $this->getFactory()->createCommentFormDataProvider();
        $form = $this->getFactory()->createCommentForm(
            $formDataProvider->getData($idSalesOrder)
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->submitCommentForm($request, $form);
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $idSalesOrder = $request->query->get(SalesConfig::PARAM_ID_SALES_ORDER);

        $comments = $this->getFacade()->getOrderCommentsByIdSalesOrder($idSalesOrder);

        return [
            'comments' => $comments->getComments(),
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function submitCommentForm(Request $request, FormInterface $form)
    {
        $formData = $form->getData();
        $idSalesOrder = $formData[CommentTransfer::FK_SALES_ORDER];

        if ($form->isValid()) {
            $commentTransfer = new CommentTransfer();
            $commentTransfer->setMessage($formData[CommentTransfer::MESSAGE]);
            $commentTransfer->setFkSalesOrder($idSalesOrder);

            $currentUserTransfer = $this->getFactory()->getUserFacade()->getCurrentUser();

            $commentTransfer->setUsername(
                $currentUserTransfer->getFirstName() . ' ' . $currentUserTransfer->getLastName()
            );

            $this->getFacade()->saveComment($commentTransfer);

            $this->addSuccessMessage('Comment successfully added');
            return $this->redirectResponse($request->headers->get('referer'));
        } else {
            foreach ($form->getErrors(true) as $error) {
                $this->addErrorMessage($error->getMessage());
            }
        }
    }

}
