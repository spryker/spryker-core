<?php

namespace SprykerFeature\Zed\Country\Communication\Controller;

use Generated\Shared\Country\CountryInterface;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\CountryCommunication;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Country\Business\CountryFacade;
use SprykerFeature\Zed\Country\Communication\CountryDependencyContainer;
use SprykerFeature\Zed\Country\CountryDependencyProvider;
use SprykerFeature\Zed\Country\Persistence\CountryQueryContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CountryQueryContainer getQueryContainer()
 * @method CountryFacade getFacade()
 * @method CountryDependencyContainer getDependencyContainer()
 */
class CountryController extends AbstractController
{

    public function indexAction(Request $request)
    {
        $form = $this->getDependencyContainer()->createCountryForm()->init();

        $form->handleRequest();

        if($form->isValid()){
            $data = $form->getData();
//
//            $country = $this->createCountryTransfer();
//            $country->fromArray($data, true);
//
//            $int = $this->getFacade()->saveCountry($country);

            $this->addMessageSuccess('Wow, so much success');
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
