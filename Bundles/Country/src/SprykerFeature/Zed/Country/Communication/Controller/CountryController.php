<?php

namespace SprykerFeature\Zed\Country\Communication\Controller;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Country\Communication\Form\CountryForm;


class CountryController extends  AbstractController
{

    public function indexAction(Request $request)
    {
        $error = false;
        $form = CountryForm::getInstance()->process($request, $error);

        return $this->viewResponse([
            'form' => $form->render(),
            'error' => $error,
        ]);
    }

}
