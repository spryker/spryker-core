<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\CommentTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\Communication\SalesCommunicationFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SalesCommunicationFactory getFactory()
 */
class CommentController extends AbstractController
{

    /**
     * @TODO check if we can remove this method
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $form = $this->getFactory()->getCommentForm();

        if ($form->isValid()) {
            $facade = $this->getFacade();

            $formData = $form->getRequestData();
            $comment = new CommentTransfer();
            $comment->setMessage($formData['message']);

            $facade->saveComment($comment);
        }

        return $this->jsonResponse($form->renderData());
    }

}
