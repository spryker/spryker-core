<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class DemoController extends AbstractController
{
    public function indexAction(Request $request)
    {
        //return $this->jsonResponse([]);
    }

    public function addAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getDemoCommentForm($request);
        $form->init();


        var_dump($form->isValid());


        return $this->jsonResponse($form->renderData());

    }
}
