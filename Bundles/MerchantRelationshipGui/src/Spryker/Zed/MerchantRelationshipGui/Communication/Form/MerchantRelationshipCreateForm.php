<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Communication\Form;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\MerchantRelationshipGui\Communication\MerchantRelationshipGuiCommunicationFactory getFactory()
 */
class MerchantRelationshipCreateForm extends AbstractType
{
    public const OPTION_SELECTED_COMPANY = 'idCompany';
    public const OPTION_COMPANY_CHOICES = 'company_choices';
    public const OPTION_MERCHANT_CHOICES = 'merchant_choices';
    public const OPTION_ASSIGNED_COMPANY_BUSINESS_UNIT_CHOICES = 'assignee_company_business_unit_choices';

    protected const FIELD_FK_COMPANY = 'fk_company';
    protected const FIELD_FK_MERCHANT = 'fk_merchant';
    protected const FIELD_FK_COMPANY_BUSINESS_UNIT = 'fk_company_business_unit';
    protected const FIELD_ASSIGNED_COMPANY_BUSINESS_UNIT = 'assigneeCompanyBusinessUnits';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'merchant-relationship';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_COMPANY_CHOICES);
        $resolver->setRequired(static::OPTION_SELECTED_COMPANY);
        $resolver->setRequired(static::OPTION_MERCHANT_CHOICES);
        $resolver->setRequired(static::OPTION_ASSIGNED_COMPANY_BUSINESS_UNIT_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addMerchantField($builder, $options[static::OPTION_MERCHANT_CHOICES])
            ->addCompanyField($builder, $options);

        if ($options[static::OPTION_SELECTED_COMPANY]) {
            $this
                ->addOwnerCompanyBusinessUnitField($builder, $options[static::OPTION_ASSIGNED_COMPANY_BUSINESS_UNIT_CHOICES])
                ->addAssignedCompanyBusinessUnitField($builder, $options[static::OPTION_ASSIGNED_COMPANY_BUSINESS_UNIT_CHOICES]);
        }
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCompanyField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_FK_COMPANY, Select2ComboBoxType::class, [
            'label' => 'Company',
            'placeholder' => 'Select company',
            'choices' => array_flip($options[static::OPTION_COMPANY_CHOICES]),
            'mapped' => false,
            'data' => $options[static::OPTION_SELECTED_COMPANY],
            'choices_as_values' => true,
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
    protected function addMerchantField(FormBuilderInterface $builder, array $choices): self
    {
        $builder->add(static::FIELD_FK_MERCHANT, Select2ComboBoxType::class, [
            'label' => 'Merchant',
            'placeholder' => 'Select merchant',
            'choices' => array_flip($choices),
            'choices_as_values' => true,
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
    protected function addOwnerCompanyBusinessUnitField(FormBuilderInterface $builder, array $choices): self
    {
        $builder->add(static::FIELD_FK_COMPANY_BUSINESS_UNIT, Select2ComboBoxType::class, [
            'label' => 'Business unit owner',
            'placeholder' => 'Select business unit',
            'choices' => array_flip($choices),
            'choices_as_values' => true,
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
    protected function addAssignedCompanyBusinessUnitField(FormBuilderInterface $builder, array $choices): self
    {
        $builder->add(static::FIELD_ASSIGNED_COMPANY_BUSINESS_UNIT, Select2ComboBoxType::class, [
            'label' => 'Assigned business units',
            'placeholder' => 'Select business units',
            'choices' => array_flip($choices),
            'choices_as_values' => true,
            'multiple' => 'true',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        $this->addModelTransformer($builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addModelTransformer(FormBuilderInterface $builder): void
    {
        $builder->get(static::FIELD_ASSIGNED_COMPANY_BUSINESS_UNIT)->addModelTransformer(
            new CallbackTransformer(
                $this->getAssignedBusinessUnitTransformer(),
                $this->getAssignedBusinessUnitReverseTransformer()
            )
        );
    }

    /**
     * @return callable
     */
    protected function getAssignedBusinessUnitTransformer(): callable
    {
        return function ($businessUnitCollection): array {
            if (!$businessUnitCollection) {
                return [];
            }
            $businessUnits = $businessUnitCollection->getCompanyBusinessUnits();
            if (empty($businessUnits)) {
                return $businessUnits;
            }
            $result = [];
            foreach ($businessUnits as $businessUnit) {
                $result[] = $businessUnit->getIdCompanyBusinessUnit();
            }

            return $result;
        };
    }

    /**
     * @return callable
     */
    private function getAssignedBusinessUnitReverseTransformer(): callable
    {
        return function ($data): CompanyBusinessUnitCollectionTransfer {
            $businessUnitCollection = new CompanyBusinessUnitCollectionTransfer();
            foreach ($data as $id) {
                $businessUnitCollection->addCompanyBusinessUnit(
                    (new CompanyBusinessUnitTransfer())
                        ->setIdCompanyBusinessUnit($id)
                );
            }

            return $businessUnitCollection;
        };
    }
}
