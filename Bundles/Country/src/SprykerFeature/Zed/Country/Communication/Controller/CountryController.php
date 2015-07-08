<?php

namespace SprykerFeature\Zed\Country\Communication\Controller;

use Generated\Shared\Transfer\CountryTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Country\Communication\Form\CountryForm;

/**
 * @method CountryQueryContainer getQueryContainer()
 */
class CountryController extends AbstractController
{

    public function indexAction()
    {
        $form = $this->getDependencyContainer()->createCountryForm()->init();
        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();
        }

        if ($request->isMethod('POST')) {
            if (false === $data = $form->processRequest($request)) {
                $errors = $this->getErrors();
                // show errors
            } else {
                // save
            }
        }

        if ($request->isMethod('POST')) {
            if (false === $data = $form->processRequest($request)) {
                $errors = $this->getErrors();
                // show errors
            } else {
                // save
            }
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

    /**
     * @return CountryTransfer
     */
    protected function createCountryTransfer()
    {
        return new CountryTransfer();
    }
}
