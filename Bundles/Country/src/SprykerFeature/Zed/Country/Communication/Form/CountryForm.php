<?php

namespace SprykerFeature\Zed\Country\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountryQuery;
use SprykerFeature\Zed\User\Persistence\Propel\Base\SpyUserUserQuery;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class CountryForm extends AbstractForm
{
    /**
     * @var SpyCountryQuery
     */
    protected $countryQuery;

    /**
     * @var SpyUserUserQuery
     */
    protected $userQuery;

    /**
     * @param SpyCountryQuery $countryQuery
     */
    public function __construct(SpyCountryQuery $countryQuery, SpyUserUserQuery $userQuery)
    {
        $this->countryQuery = $countryQuery;
        $this->userQuery = $userQuery;
    }

    /**
     * @return $this
     */
    protected function buildFormFields()
    {
        $userChoice = $this->prepareUserChoice();

        return $this->addText(
            'iso2_code',
            [
                'label' => 'ISO2 Code',
                'constraints' => [
                    new NotBlank([
                            'message' => 'woooohoooo'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 2
                    ])
                ]
            ]
        )
            ->addText('iso3_code',
                [
                    'label' => 'ISO3 Code',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 3,
                            'max' => 3
                        ])
                    ]
                ]
            )
            ->addText('name',
                [
                    'label' => 'Country Name',
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]
            )
            ->addText('postal_code_mandatory',
                [
                    'label' => 'Postal Code',
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]
            )
            ->addChoice('myChoice',
                [
                    'label' => 'Username',
                    'choices' => $userChoice,
                ])
            ->addSelect('blabla', [
                'label' => 'Select',
                'placeholder' => 'asdasdasdasdasda2',
                'url' => '/asdasd/'
            ])
            ->addText('postal_code_regex', ['label' => 'Postal code (regex)'])
            ->addHidden('id_country')
            ->addSubmit();

    }

    /**
     * @return array
     */
    protected function prepareUserChoice()
    {
        $users = $this->userQuery->find();
        $userChoice = [];
        foreach ($users as $user) {
            $userChoice[$user->getIdUserUser()] = $user->getUsername();
        }
        return $userChoice;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $idCountry = $this->request->get('id_country');

        if (is_null($idCountry)) {
            return [];
        }

        $countryDetailEntity = $this
            ->countryQuery
            ->findOneByIdCountry($idCountry);

        $data = $countryDetailEntity->toArray();
        return $data;
    }
}
