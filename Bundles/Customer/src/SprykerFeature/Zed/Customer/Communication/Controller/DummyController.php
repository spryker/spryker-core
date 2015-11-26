<?php

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Communication\Form\DummyForm;
use SprykerFeature\Zed\Customer\Communication\Form\DummyFormType;
use Symfony\Component\HttpFoundation\Request;

class DummyController extends AbstractController
{

    public function indexAction(Request $request)
    {

        $form = $this->getDependencyContainer()->getDummyForm();
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
