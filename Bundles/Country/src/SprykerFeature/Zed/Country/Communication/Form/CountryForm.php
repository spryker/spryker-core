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

    /**
     * @return $this
     */
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
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $idCountry = $this->request->get('id_country');

        $countryDetailEntity = $this
            ->countryQuery
            ->findOneByIdCountry($idCountry);

        $data = $countryDetailEntity->toArray();
        return $data;
    }
}
