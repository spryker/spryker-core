<?php

namespace SprykerFeature\Zed\Country\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\User\Persistence\Base\SpyUserQuery;

class CountryForm extends AbstractForm
{

    /**
     * @var SpyCountryQuery
     */
    protected $countryQuery;

    /**
     * @var SpyUserQuery
     */
    protected $userQuery;

    /**
     * @param SpyCountryQuery $countryQuery
     */
    public function __construct(SpyCountryQuery $countryQuery, SpyUserQuery $userQuery)
    {
        $this->countryQuery = $countryQuery;
        $this->userQuery = $userQuery;
    }

    /**
     * @return self
     */
    protected function buildFormFields()
    {
        return $this->addText(
            'iso2_code',
            [
                'label' => 'ISO2 Code',
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank([
                        'message' => 'Please provide correct ISO2 Code',
                    ]),
                    $this->getConstraints()->createConstraintLength([
                        'min' => 2,
                        'max' => 2,
                    ]),
                ],
            ]
        )
            ->addText('iso3_code',
                [
                    'label' => 'ISO3 Code',
                    'constraints' => [
                        $this->getConstraints()->createConstraintNotBlank(),
                        $this->getConstraints()->createConstraintLength([
                            'min' => 3,
                            'max' => 3,
                        ]),
                    ],
                ]
            )
            ->addText('name',
                [
                    'label' => 'Country Name',
                    'constraints' => [
                        $this->getConstraints()->createConstraintNotBlank(),
                    ],
                ]
            )
            ->addCheckbox('postal_code_mandatory',
                [
                    'label' => 'Is postal code mandatory',
                ]
            )
            ->addText('postal_code_regex',
                [
                    'label' => 'Postal code (regex)',
                ]
            )
            ->addHidden('id_country')
        ;

    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $result = [];

        $idCountry = $this->request->get('id_country');
        if (false === is_null($idCountry)) {
            $countryDetailEntity = $this
                ->countryQuery
                ->findOneByIdCountry($idCountry);

            $result = $countryDetailEntity->toArray();
        }

        return $result;
    }

}
