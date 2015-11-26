<?php

namespace SprykerFeature\Zed\Customer\Communication\Form;

use Generated\Shared\Transfer\CountryTransfer;
use Pyz\Zed\Country\Business\CountryFacade;
use SprykerEngine\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * This class is only for tests and for documentation.
 *
 * @todo remove me
 */
class DummyForm extends AbstractForm
{

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
        $builder
            ->add(CountryTransfer::ID_COUNTRY, 'text')
            ->add(CountryTransfer::ISO2_CODE, 'text')
            ->add(CountryTransfer::NAME, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ]
            ])
        ;
    }

    /**
     * @return CountryTransfer
     */
    public function populateFormFields()
    {
        $name = $this->getRequest()->query->get('name');

        $country = $this->countryFacade->getPreferedCountryByName($name);

        if ($country->getIdCountry() > 0) {
            return $country;
        }

        return $this->getDataClass();
    }

    /**
     * @return CountryTransfer
     */
    protected function getDataClass()
    {
        return new CountryTransfer();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dummy';
    }

}
