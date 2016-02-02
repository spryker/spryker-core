<?php

namespace Spryker\Zed\Sales\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CustomerForm extends AbstractType
{

    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_SALUTATION = 'salutation';
    const FIELD_EMAIL = 'email';

    const OPTION_SALUTATION_CHOICES = 'salutation_choices';

    /**
     * @return string
     */
    public function getName()
    {
        return 'customer';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(self::OPTION_SALUTATION_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addSalutationField($builder, $options[self::OPTION_SALUTATION_CHOICES])
            ->addFirstNameField($builder)
            ->addLastNameField($builder)
            ->addEmailField($builder);
    }

    /**
     * @param array $choices
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return self
     */
    public function addSalutationField(FormBuilderInterface $builder, array $choices)
    {
        $builder
            ->add(self::FIELD_SALUTATION, 'choice', [
                'label' => 'Salutation',
                'placeholder' => '-select-',
                'choices' => $choices,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return self
     */
    public function addFirstNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_FIRST_NAME, 'text', [
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return self
     */
    public function addLastNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_LAST_NAME, 'text', [
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return self
     */
    public function addEmailField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_EMAIL, 'text', [
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

}
