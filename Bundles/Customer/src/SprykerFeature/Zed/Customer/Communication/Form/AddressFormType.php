<?php

namespace SprykerFeature\Zed\Customer\Communication\Form;

use SprykerEngine\Zed\Gui\Communication\Form\AbstractFormType;
use SprykerFeature\Zed\Country\Business\CountryFacade;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Symfony\Component\Form\FormBuilderInterface;

class AddressFormType extends AbstractFormType
{

    const FIELD_SALUTATION = 'salutation';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_ID_CUSTOMER = 'id_customer';
    const FIELD_ID_CUSTOMER_ADDRESS = 'id_customer_address';
    const FIELD_FK_CUSTOMER = 'fk_customer';
    const FIELD_ADDRESS_1 = 'address1';
    const FIELD_ADDRESS_2 = 'address2';
    const FIELD_ADDRESS_3 = 'address3';
    const FIELD_CITY = 'city';
    const FIELD_ZIP_CODE = 'zip_code';
    const FIELD_FK_COUNTRY = 'fk_country';
    const FIELD_PHONE = 'phone';
    const FIELD_COMPANY = 'company';
    const FIELD_COMMENT = 'comment';

    const PREFERED_COUNTRY_NAME = 'Germany';

    /**
     * @var CountryFacade
     */
    protected $countryFacade;

    /**
     * @param CountryFacade $countryFacade
     */
    public function __construct(CountryFacade $countryFacade)
    {
        $this->countryFacade = $countryFacade;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $preferedCountry = $this->countryFacade
            ->getPreferedCountryByName(self::PREFERED_COUNTRY_NAME);

        $builder
            ->add(self::FIELD_ID_CUSTOMER_ADDRESS, 'hidden')
            ->add(self::FIELD_FK_CUSTOMER, 'hidden')
            ->add(self::FIELD_SALUTATION, 'choice', [
                'label' => 'Salutation',
                'placeholder' => 'Select one',
                'choices' => $this->getSalutationOptions(),
            ])
            ->add(self::FIELD_FIRST_NAME, 'text', [
                'label' => 'First Name',
                'constraints' => $this->getTextFieldConstraints(),
            ])
            ->add(self::FIELD_LAST_NAME, 'text', [
                'label' => 'Last Name',
                'constraints' => $this->getTextFieldConstraints(),
            ])
            ->add(self::FIELD_ADDRESS_1, 'text', [
                'label' => 'Address line 1',
            ])
            ->add(self::FIELD_ADDRESS_2, 'text', [
                'label' => 'Address line 2',
            ])
            ->add(self::FIELD_ADDRESS_3, 'text', [
                'label' => 'Address line 3',
            ])
            ->add(self::FIELD_CITY, 'text', [
                'label' => 'City',
            ])
            ->add(self::FIELD_ZIP_CODE, 'text', [
                'label' => 'Zip Code',
                'constraints' => [
                    $this->getConstraints()->createConstraintLength(['max' => 15]),
                ],
            ])
            ->add(self::FIELD_FK_COUNTRY, 'choice', [
                'label' => 'Country',
                'placeholder' => 'Select one',
                'choices' => $this->getCountryOptions(),
                'preferred_choices' => [
                    $preferedCountry->getIdCountry(),
                ],
            ])
            ->add(self::FIELD_PHONE, 'text', [
                'label' => 'Phone',
            ])
            ->add(self::FIELD_COMPANY, 'text', [
                'label' => 'Company',
            ])
            ->add(self::FIELD_COMMENT, 'textarea', [
                'label' => 'Comment',
                'constraints' => [
                    $this->getConstraints()->createConstraintLength(['max' => 255]),
                ],
            ]);
    }

    /**
     * @return array
     */
    public function getCountryOptions()
    {
        $countryCollection = $this->countryFacade->getAvailableCountries();

        $result = [];
        if ($countryCollection->getCountries()->count() > 0) {
            foreach ($countryCollection->getCountries() as $country) {
                $result[$country->getIdCountry()] = $country->getName();
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getSalutationOptions()
    {
        $salutation = SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_SALUTATION);

        return $this->getEnumSet($salutation);
    }

    /**
     * @return array
     */
    protected function getTextFieldConstraints()
    {
        return [
            $this->getConstraints()->createConstraintRequired(),
            $this->getConstraints()->createConstraintNotBlank(),
            $this->getConstraints()->createConstraintLength([
                'max' => 100,
            ]),
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'customer_address';
    }

}
