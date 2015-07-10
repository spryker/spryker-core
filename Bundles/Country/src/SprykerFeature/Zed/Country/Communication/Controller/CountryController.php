<?php

namespace SprykerFeature\Zed\Country\Communication\Controller;

use Generated\Shared\Country\CountryInterface;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\CountryCommunication;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Country\Business\CountryFacade;
use SprykerFeature\Zed\Country\Communication\CountryDependencyContainer;
use SprykerFeature\Zed\Country\Persistence\CountryQueryContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CountryQueryContainer getQueryContainer()
 * @method CountryFacade getFacade()
 * @method CountryDependencyContainer getDependencyContainer()
 */
class CountryController extends AbstractController
{

    public function indexAction()
    {
        $form = $this->getDependencyContainer()->createCountryForm()->init();
        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();

           # $country = $this->createCountryTransfer();
           # $country->fromArray($data, true);

            #$lastInsertedId = $this->getFacade()->saveCountry($country);
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

    public function ajaxAction(Request $request)
    {
        $data = [
            'results' => [
                [
                    'id'    => 1,
                    'text' => 'Blablacar',
                ],
                [
                    'id'    => 2,
                    'text' => 'Uber',
                ],
                [
                    'id'    => 3,
                    'text' => 'Uberwe',
                ],
                [
                    'id' => 4,
                    'text' => 'Uberer',
                ],
                [
                    'id'=> 5,
                    'text' => 'Ubegj r',
                ],
                [
                    'id' => 6,
                    'text' => 'Ub   gher',
                ],
            ],
            'more' => false,
        ];

        return $this->jsonResponse($data);
    }

    /**
     * @return CountryTransfer
     */
    protected function createCountryTransfer()
    {
        return new CountryTransfer();
    }

}
