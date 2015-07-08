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

    public function indexAction(Request $request)
    {
        $countryId = intval($request->get('id_country', false));

        $countryDetailsEntity = $this->getQueryContainer()->queryCountries()->findOneByIdCountry($countryId);
        $countryDetails = $countryDetailsEntity->toArray();

        $form = CountryForm::getInstance()->process($request, $countryDetails);

        return $this->viewResponse([
            'form' => $form->render(),
        ]);
    }

}
