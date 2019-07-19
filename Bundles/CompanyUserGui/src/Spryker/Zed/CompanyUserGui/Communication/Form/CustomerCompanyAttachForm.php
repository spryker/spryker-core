<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Form;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\CompanyUserGui\Communication\CompanyUserGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyUserGui\CompanyUserGuiConfig getConfig()
 */
class CustomerCompanyAttachForm extends AbstractType
{
    public const OPTION_COMPANY_CHOICES = 'company_choices';

    public const FIELD_FK_COMPANY = 'fk_company';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'company_user_to_company';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(static::OPTION_COMPANY_CHOICES);
        $resolver->setDefaults([
            'data_class' => CompanyUserTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCompanyField($builder, $options[static::OPTION_COMPANY_CHOICES])
            ->executeAttachCustomerFormExpanderPlugins($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addCompanyField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_FK_COMPANY, ChoiceType::class, [
            'label' => 'Company',
            'placeholder' => 'Company name',
            'choices' => $choices,
            'constraints' => [
                new NotBlank(),
                new GreaterThan([
                    'value' => 0,
                    'message' => 'Select company.',
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function executeAttachCustomerFormExpanderPlugins(FormBuilderInterface $builder)
    {
        foreach ($this->getFactory()->getCompanyUserAttachCustomerFormExpanderPlugins() as $attachCustomerFormExpanderPlugin) {
            $builder = $attachCustomerFormExpanderPlugin->expand($builder);
        }

        return $this;
    }
}
