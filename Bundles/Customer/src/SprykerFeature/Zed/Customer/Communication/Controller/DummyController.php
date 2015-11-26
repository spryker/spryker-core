<?php

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DummyController extends AbstractController
{

    public function indexAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getDummyForm($request);
        $form->handleRequest($request);

        if ($form->isValid()) {
//            $this->getFacade()->save($form->getData());
            dump($form->getData());die;
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
