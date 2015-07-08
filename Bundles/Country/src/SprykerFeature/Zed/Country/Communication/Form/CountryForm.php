<?php

namespace SprykerFeature\Zed\Country\Communication\Form;

use SprykerEngine\Zed\Gui\Communication\Form\AbstractForm;
use SprykerEngine\Zed\Gui\Communication\Form\ConstraintBuilder;
use SprykerFeature\Zed\Country\Persistence\CountryQueryContainer;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountryQuery;

/**
 * @method CountryQueryContainer getQueryContainer()
 */
class CountryForm extends AbstractForm
{

    /**
     * @var SpyCountryQuery
     */
    protected $countryQuery;

    /**
     * @param SpyCountryQuery $countryQuery
     */
    public function __construct(SpyCountryQuery $countryQuery)
    {
        $this->countryQuery = $countryQuery;
    }


    public function process()
    {
        $idCountry = $this->request->get('id_country');

        $countryDetailEntity = $this
            ->countryQuery
            ->findOneByIdCountry($idCountry);

        $data = $countryDetailEntity->toArray();

        // TODO this should not be here
        if ($this->request->isMethod('POST')) {
            if (false === $data = $this->processRequest($this->request)) {
                $errors = $this->getErrors();
            }else{
                // all ok
            }
        }

//        return false === empty($data) ?
//            $this->updateCountry($data) :
//            $this->createCountry();
    }

//    public function updateCountry($data)
//    {
//        return $this->buildForm($data);
//    }

    protected function populateFormFields()
    {
        $idCountry = $this->request->get('id_country');

        $countryDetailEntity = $this
            ->countryQuery
            ->findOneByIdCountry($idCountry);

        $data = $countryDetailEntity->toArray();
        return $data;
    }

    protected function buildFormFields()
    {
        return $this->addText(
            'iso2_code',
            [
                'label' => 'ISO2 Code',
                'constraints' => ConstraintBuilder::getInstance()
                    ->addNotBlank()
                    ->addLength([
                        'min' => 2,
                        'max' => 2
                    ])
                    ->getConstraints()
            ]
        )
            ->addText('iso3_code',
                [
                    'label' => 'ISO3 Code',
                    'constraints' => ConstraintBuilder::getInstance()
                        ->addNotBlank()
                        ->addLength([
                            'min' => 3,
                            'max' => 3
                        ])
                        ->getConstraints()
                ])
            ->addText('name',
                [
                    'label' => 'Country Name',
                    'constraints' => ConstraintBuilder::getInstance()
                        ->addNotBlank()
                        ->getConstraints()
                ])
            ->addText('postal_code_mandatory',
                [
                    'label' => 'Postal Code',
                    'constraints' => ConstraintBuilder::getInstance()
                        ->addNotBlank()
                        ->getConstraints()
                ])
            ->addText('postal_code_regex', ['label' => 'Postal code (regex)'])
            ->addHidden('id_country')
            ->addSubmit();
//            ->setData($data);
    }

    public function createCountry()
    {
        $data = [];

        return $this->buildForm($data);
    }
}
