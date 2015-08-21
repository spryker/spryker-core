<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\CommentTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\Communication\SalesDependencyContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class CommentController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getCommentForm($request);

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
