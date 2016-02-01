<?php

namespace Spryker\Zed\Sales\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddressForm extends AbstractType
{

    const FIELD_SALUTATION = 'salutation';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_MIDDLE_NAME = 'middle_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_EMAIL = 'email';
    const FIELD_ADDRESS_1 = 'address1';
    const FIELD_COMPANY = 'company';
    const FIELD_CITY = 'city';
    const FIELD_ZIP_CODE = 'zip_code';
    const FIELD_PO_BOX = 'po_box';
    const FIELD_PHONE = 'phone';
    const FIELD_CELL_PHONE = 'cell_phone';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_COMMENT = 'comment';

    const OPTION_SALUTATION_CHOICES = 'salutation_choices';

    /**
     * @return string
     */
    public function getName()
    {
        return 'address';
    }

    /**
     * @param OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(self::OPTION_SALUTATION_CHOICES);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addSalutationField($builder, $options[self::OPTION_SALUTATION_CHOICES])
            ->addFirstNameField($builder)
            ->addMiddleNameField($builder)
            ->addLastNameField($builder)
            ->addEmailField($builder)
            ->addAddress1Field($builder)
            ->addCompanyField($builder)
            ->addCityField($builder)
            ->addZipCodeField($builder)
            ->addPoBoxField($builder)
            ->addPhoneField($builder)
            ->addCellPhoneField($builder)
            ->addDescriptionField($builder)
            ->addCommentField($builder);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $choices
     *
     * @return AddressForm
     */
    protected function addSalutationField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(self::FIELD_SALUTATION, 'choice', [
            'label' => 'Salutation',
            'placeholder' => '-select-',
            'choices' => $choices,
        ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addFirstNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_FIRST_NAME, 'text', [
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addMiddleNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_MIDDLE_NAME, 'text');

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addLastNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_LAST_NAME, 'text', [
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_EMAIL, 'text', [
            'constraints' => [
                new NotBlank(),
                new Email(),
            ],
        ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addAddress1Field(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ADDRESS_1, 'text');

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addCompanyField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_COMPANY, 'text');

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addCityField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_CITY, 'text');

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addZipCodeField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ZIP_CODE, 'text');

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addPoBoxField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_PO_BOX, 'text');

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addPhoneField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_PHONE, 'text');

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addCellPhoneField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_CELL_PHONE, 'text');

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addDescriptionField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_DESCRIPTION, 'text');

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addCommentField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_COMMENT, 'textarea');

        return $this;
    }

}
