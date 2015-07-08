<?php

namespace SprykerFeature\Zed\Country\Communication\Controller;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Country\Communication\Form\CountryForm;

/**
 * @method CountryQueryContainer getQueryContainer()
 */
class CountryController extends  AbstractController
{

    public function indexAction()
    {
        $form = $this->getDependencyContainer()->createCountryForm()->init();
        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();

            // $country = $this->createCountryTransfer();
            // $country->fromArray($data, true);

            // $lastInsertedId = $this->getFacade()->saveCountry($country);
        }

        if ($request->isMethod('POST')) {
            if (false === $data = $form->processRequest($request)) {
                $errors = $this->getErrors();
                // show errors
            } else {
                // save
            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

}
