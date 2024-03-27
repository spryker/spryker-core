<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MerchantRelationshipMerchantPortalGui\MerchantRelationshipMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\MerchantRelationshipMerchantPortalGuiCommunicationFactory getFactory()
 */
class MerchantRelationshipForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_ASSIGNEE_COMPANY_BUSINESS_UNITS_CHOICES = 'assignee_company_business_units_choices';

    /**
     * @var string
     */
    public const FIELD_SAVE = 'save';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'merchantRelationship';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MerchantRelationshipTransfer::class,
        ]);

        $resolver->setRequired(static::OPTION_ASSIGNEE_COMPANY_BUSINESS_UNITS_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addSaveButtonField($builder)
            ->addOwnerCompanyBusinessUnitSubform($builder)
            ->addAssigneeCompanyBusinessUnitsField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addSaveButtonField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SAVE, SubmitType::class, [
            'label' => 'Save',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOwnerCompanyBusinessUnitSubform(FormBuilderInterface $builder)
    {
        $builder->add(MerchantRelationshipTransfer::OWNER_COMPANY_BUSINESS_UNIT, OwnerCompanyBusinessUnitForm::class, [
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addAssigneeCompanyBusinessUnitsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(MerchantRelationshipTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS, ChoiceType::class, [
            'label' => 'Business Units',
            'multiple' => true,
            'choices' => array_flip($options[static::OPTION_ASSIGNEE_COMPANY_BUSINESS_UNITS_CHOICES]),
            'property_path' => 'assigneeCompanyBusinessUnits.companyBusinessUnits',
            'attr' => [
                'class' => 'form-control',
            ],
        ]);

        $builder->get(MerchantRelationshipTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS)->addModelTransformer(
            $this->getFactory()->createAssigneeCompanyBusinessUnitsDataTransformer(),
        );

        return $this;
    }
}
