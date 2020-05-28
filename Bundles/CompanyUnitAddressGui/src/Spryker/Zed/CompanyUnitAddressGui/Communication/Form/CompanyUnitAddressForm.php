<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication\Form;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\CompanyUnitAddressGui\Communication\CompanyUnitAddressGuiCommunicationFactory getFactory()
 */
class CompanyUnitAddressForm extends AbstractType
{
    public const OPTION_COMPANY_CHOICES = 'company_choices';
    public const OPTION_COUNTRY_CHOICES = 'country_choices';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_COMPANY_CHOICES);
        $resolver->setRequired(static::OPTION_COUNTRY_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIdCompanyUnitAddressField($builder)
            ->addCompanyField($builder, $options[static::OPTION_COMPANY_CHOICES])
            ->addCountryField($builder, $options[static::OPTION_COUNTRY_CHOICES])
            ->addCityField($builder)
            ->addZipCodeField($builder)
            ->addAddress1Field($builder)
            ->addAddress2Field($builder)
            ->addAddress3Field($builder)
            ->addCommentField($builder)
            ->executeCompanyUnitAddressFormPlugins($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCompanyUnitAddressField(FormBuilderInterface $builder)
    {
        $builder->add(CompanyUnitAddressTransfer::ID_COMPANY_UNIT_ADDRESS, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addCompanyField(FormBuilderInterface $builder, array $choices = [])
    {
        $builder->add(CompanyUnitAddressTransfer::FK_COMPANY, ChoiceType::class, [
            'label' => 'Company',
            'placeholder' => 'Select one',
            'choices' => array_flip($choices),
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addCountryField(FormBuilderInterface $builder, array $choices = [])
    {
        $builder->add(CompanyUnitAddressTransfer::FK_COUNTRY, ChoiceType::class, [
            'label' => 'Country',
            'placeholder' => 'Select one',
            'choices' => array_flip($choices),
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCityField(FormBuilderInterface $builder)
    {
        $builder->add(CompanyUnitAddressTransfer::CITY, TextType::class, [
            'label' => 'City',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addZipCodeField(FormBuilderInterface $builder)
    {
        $builder->add(CompanyUnitAddressTransfer::ZIP_CODE, TextType::class, [
            'label' => 'Zip Code',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 10]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAddress1Field(FormBuilderInterface $builder)
    {
        $builder->add(CompanyUnitAddressTransfer::ADDRESS1, TextType::class, [
            'label' => 'Street',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAddress2Field(FormBuilderInterface $builder)
    {
        $builder->add(CompanyUnitAddressTransfer::ADDRESS2, TextType::class, [
            'label' => 'Number',
            'required' => false,
            'constraints' => [
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAddress3Field(FormBuilderInterface $builder)
    {
        $builder->add(CompanyUnitAddressTransfer::ADDRESS3, TextType::class, [
            'label' => 'Addition to address',
            'required' => false,
            'constraints' => [
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCommentField(FormBuilderInterface $builder)
    {
        $builder->add(CompanyUnitAddressTransfer::COMMENT, TextareaType::class, [
            'label' => 'Comment',
            'required' => false,
            'constraints' => [
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function executeCompanyUnitAddressFormPlugins(FormBuilderInterface $builder)
    {
        foreach ($this->getFactory()->getCompanyUnitAddressFormPlugins() as $formPlugin) {
            $formPlugin->buildForm($builder);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'company_unit_address';
    }
}
