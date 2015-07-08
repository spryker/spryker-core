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

        return $this->viewResponse([
            'form' => $form->render(),
        ]);
    }

}
