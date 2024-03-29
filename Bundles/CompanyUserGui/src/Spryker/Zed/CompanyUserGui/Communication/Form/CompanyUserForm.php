<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Form;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\CompanyUserGui\Communication\CompanyUserGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyUserGui\CompanyUserGuiConfig getConfig()
 */
class CompanyUserForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_ID_COMPANY_USER = 'id_company_user';

    /**
     * @var string
     */
    public const FIELD_FK_COMPANY = 'fk_company';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'company-user';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(CompanyUserCustomerForm::OPTION_SALUTATION_CHOICES);
        $resolver->setDefined(CompanyUserCustomerForm::OPTION_GENDER_CHOICES);
        $resolver->setDefaults([
            'data_class' => CompanyUserTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addIdCompanyUserField($builder)
            ->addCustomerSubForm($builder, $options)
            ->executeCompanyUserExpanderFormPlugins($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addCustomerSubForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            CompanyUserTransfer::CUSTOMER,
            CompanyUserCustomerForm::class,
            [
                'data_class' => CustomerTransfer::class,
                'label' => false,
                CompanyUserCustomerForm::OPTION_GENDER_CHOICES => $options[CompanyUserCustomerForm::OPTION_GENDER_CHOICES],
                CompanyUserCustomerForm::OPTION_SALUTATION_CHOICES => $options[CompanyUserCustomerForm::OPTION_SALUTATION_CHOICES],
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCompanyUserField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_COMPANY_USER, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function executeCompanyUserExpanderFormPlugins(FormBuilderInterface $builder)
    {
        foreach ($this->getFactory()->getCompanyUserFormPlugins() as $companyUserFormExpanderPlugin) {
            $builder = $companyUserFormExpanderPlugin->expand($builder);
        }

        return $this;
    }
}
