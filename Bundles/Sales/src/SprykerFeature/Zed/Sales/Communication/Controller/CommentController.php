<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\CommentTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method UrlDependencyContainer getDependencyContainer()
 */
class CommentController extends AbstractController
{
    public function addAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getCommentForm($request);
        $form->init();

//        if ($form->isValid()) {
//            $facade = $this->getLocator()->sales()->facade();
//
//            $formData = $form->getRequrstData();
//            $comment = new CommentTransfer();
//            $comment->setMessage($formData['comment']);
//
//            $facade->saveComment($comment);
//        }

        return $this->jsonResponse($form->renderData());
    }
}
