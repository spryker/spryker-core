<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\CommentTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 */
class CommentController extends AbstractController
{

    /**
     * @TODO check if we can remove this method
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addAction(Request $request)
    {
        $form = $this->getFactory()->getCommentForm();
        $form->handleRequest($request);

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
