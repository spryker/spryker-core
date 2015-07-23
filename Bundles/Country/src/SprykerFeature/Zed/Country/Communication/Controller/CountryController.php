<?php

namespace SprykerFeature\Zed\Country\Communication\Controller;

use Generated\Shared\Transfer\CountryTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

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
