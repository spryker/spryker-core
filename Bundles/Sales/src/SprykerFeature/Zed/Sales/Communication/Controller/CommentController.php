<?php

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
    public function addAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getCommentForm($request);
        $form->init();

//        if ($request->isMethod('POST') && $form->isValid()) {
        if ($form->isValid()) {
            $facade = $this->getLocator()->sales()->facade();

            $formData = $form->getRequestData();
            $comment = new CommentTransfer();
            $comment->setMessage($formData['message']);

            $facade->saveComment($comment);
//            var_dump('-save-');
        } else {
//            var_dump(get_class_methods(get_class($form))/* $form->getPlugins()*/);
//            var_dump($form);
//            die;
        }

        return $this->jsonResponse($form->renderData());
    }
}
