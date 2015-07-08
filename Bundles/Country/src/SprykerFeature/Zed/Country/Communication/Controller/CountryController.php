<?php

namespace SprykerFeature\Zed\Country\Communication\Controller;

use Generated\Zed\Ide\FactoryAutoCompletion\CountryCommunication;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Country\Persistence\CountryQueryContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CountryQueryContainer getQueryContainer()
 * @method CountryCommunication getFactory()
 */
class CountryController extends AbstractController
{

    public function indexAction(Request $request)
    {
        $countryQuery = $this->getQueryContainer()->queryCountries();

        $form = $this->getFactory()->createFormCountryForm($countryQuery)->init();

        if ($request->isMethod('POST')) {
            if (false === $data = $form->processRequest($request)) {
                $errors = $this->getErrors();
                // show errors
            } else {
                // save
            }
        }

        return $this->viewResponse([
            'form' => $form->render(),
        ]);
    }

}
