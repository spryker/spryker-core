<?php

namespace Spryker\Zed\Customer\Communication\Form;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Shared\Gui\Form\AbstractForm;
use Spryker\Zed\Customer\CustomerConfig;
use Spryker\Zed\Country\Business\CountryFacade;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainer;
use Symfony\Component\Form\FormBuilderInterface;

class AddressForm extends AbstractForm
{

    const PREFERED_COUNTRY_NAME = 'Germany';

    /**
     * @var CountryFacade
     */
    protected $countryFacade;

    /**
     * @var CustomerQueryContainer
     */
    protected $customerQueryContainer;

    /**
     * @param CountryFacade $countryFacade
     * @param CustomerQueryContainer $queryContainer
     */
    public function __construct(CustomerToCountryInterface $countryFacade, CustomerQueryContainer $queryContainer)
    {
        $this->countryFacade = $countryFacade;
        $this->customerQueryContainer = $queryContainer;
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        $idCustomerAddress = $this->getRequest()->query->getInt(CustomerConfig::PARAM_ID_CUSTOMER_ADDRESS);

        if ($idCustomerAddress === 0) {
            return $this->getDataClass();
        }

        $address = $this->customerQueryContainer->queryAddress($idCustomerAddress)->findOne();

        $addressTransfer = $this->getDataClass();
        $addressTransfer->fromArray($address->toArray(), true);

        return $addressTransfer;
    }

    /**
     * @return AddressTransfer
     */
    protected function getDataClass()
    {
        return new AddressTransfer();
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
            ->add(AddressTransfer::ID_CUSTOMER_ADDRESS, 'hidden')
            ->add(AddressTransfer::FK_CUSTOMER, 'hidden')
            ->add(AddressTransfer::SALUTATION, 'choice', [
                'label' => 'Salutation',
                'placeholder' => 'Select one',
                'choices' => $this->getSalutationOptions(),
            ])
            ->add(AddressTransfer::FIRST_NAME, 'text', [
                'label' => 'First Name',
                'constraints' => $this->getTextFieldConstraints(),
            ])
            ->add(AddressTransfer::LAST_NAME, 'text', [
                'label' => 'Last Name',
                'constraints' => $this->getTextFieldConstraints(),
            ])
            ->add(AddressTransfer::ADDRESS1, 'text', [
                'label' => 'Address line 1',
            ])
            ->add(AddressTransfer::ADDRESS2, 'text', [
                'label' => 'Address line 2',
            ])
            ->add(AddressTransfer::ADDRESS3, 'text', [
                'label' => 'Address line 3',
            ])
            ->add(AddressTransfer::CITY, 'text', [
                'label' => 'City',
            ])
            ->add(AddressTransfer::ZIP_CODE, 'text', [
                'label' => 'Zip Code',
                'constraints' => [
                    $this->getConstraints()->createConstraintLength(['max' => 15]),
                ],
            ])
            ->add(AddressTransfer::FK_COUNTRY, 'choice', [
                'label' => 'Country',
                'placeholder' => 'Select one',
                'choices' => $this->getCountryOptions(),
                'preferred_choices' => [
                    $preferedCountry->getIdCountry(),
                ],
            ])
            ->add(AddressTransfer::PHONE, 'text', [
                'label' => 'Phone',
            ])
            ->add(AddressTransfer::COMPANY, 'text', [
                'label' => 'Company',
            ])
            ->add(AddressTransfer::COMMENT, 'textarea', [
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
