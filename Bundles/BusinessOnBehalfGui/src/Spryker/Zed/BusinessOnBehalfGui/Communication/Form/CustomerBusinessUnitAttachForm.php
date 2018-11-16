<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\Form;

use Closure;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\BusinessOnBehalfGui\Communication\BusinessOnBehalfGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\BusinessOnBehalfGui\BusinessOnBehalfGuiConfig getConfig()
 */
class CustomerBusinessUnitAttachForm extends AbstractType
{
    public const OPTION_VALUES_BUSINESS_UNITS_CHOICES = 'company_business_unit_choices';
    public const OPTION_ATTRIBUTES_BUSINESS_UNITS_CHOICES = 'company_business_unit_attributes';
    public const OPTION_VALUES_ROLES_CHOICES = 'company_role_choices';
    public const OPTION_ATTRIBUTES_ROLES_CHOICES = 'company_role_attributes';

    public const FIELD_FK_COMPANY_BUSINESS_UNIT = 'fk_company_business_unit';
    protected const FIELD_COMPANY_ROLE_COLLECTION = 'company_role_collection';

    public const FIELD_FK_COMPANY = 'fk_company';
    protected const TEMPLATE_PATH = '@BusinessOnBehalfGui/CreateCompanyUser/company_role.twig';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'customer_business_unit_attach';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_VALUES_BUSINESS_UNITS_CHOICES);
        $resolver->setRequired(static::OPTION_ATTRIBUTES_BUSINESS_UNITS_CHOICES);

        $resolver->setRequired(static::OPTION_VALUES_ROLES_CHOICES);
        $resolver->setRequired(static::OPTION_ATTRIBUTES_ROLES_CHOICES);

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
        $this->addCompanyBusinessUnitCollectionField($builder, $options);
        $this->addCompanyRoleCollectionField($builder, $options);
        $this->addFkCompanyField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCompanyBusinessUnitCollectionField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_FK_COMPANY_BUSINESS_UNIT, ChoiceType::class, [
            'label' => 'Business Unit',
            'placeholder' => 'Business Unit name',
            'choices' => $options[static::OPTION_VALUES_BUSINESS_UNITS_CHOICES],
            'choice_attr' => $options[static::OPTION_ATTRIBUTES_BUSINESS_UNITS_CHOICES],
            'choices_as_values' => true,
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCompanyRoleCollectionField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_COMPANY_ROLE_COLLECTION, ChoiceType::class, [
            'choices' => $options[static::OPTION_VALUES_ROLES_CHOICES],
            'choice_attr' => $options[static::OPTION_ATTRIBUTES_ROLES_CHOICES],
            'constraints' => $this->createCompanyRoleCollectionConstraints(),
            'choices_as_values' => true,
            'label' => false,
            'expanded' => true,
            'required' => true,
            'multiple' => true,
            'attr' => [
                'template_path' => $this->getTemplatePath(),
            ],
        ]);

        $callbackTransformer = new CallbackTransformer(
            $this->getInputDataCallbackRoleCollectionTransformer(),
            $this->getOutputDataCallbackRoleCollectionTransformer()
        );

        $builder->get(static::FIELD_COMPANY_ROLE_COLLECTION)
            ->addModelTransformer($callbackTransformer);

        return $this;
    }

    /**
     * @return array
     */
    protected function createCompanyRoleCollectionConstraints(): array
    {
        $companyRoleCollectionConstraints = [];

        $companyRoleCollectionConstraints[] = new Callback([
            'callback' => function (CompanyRoleCollectionTransfer $companyRoleCollectionTransfer, ExecutionContextInterface $context) {
                if (!$companyRoleCollectionTransfer->getRoles()->count()) {
                    $context->addViolation('At least one role must be assigned to a user.');
                }
            },
        ]);

        return $companyRoleCollectionConstraints;
    }

    /**
     * @return \Closure
     */
    protected function getInputDataCallbackRoleCollectionTransformer(): Closure
    {
        return function ($roleCollection = []): array {
            $roles = [];

            if (!empty($roleCollection[CompanyRoleCollectionTransfer::ROLES])) {
                foreach ($roleCollection[CompanyRoleCollectionTransfer::ROLES] as $role) {
                    $roles[] = $role[CompanyRoleTransfer::ID_COMPANY_ROLE];
                }
            }

            return $roles;
        };
    }

    /**
     * @return \Closure
     */
    protected function getOutputDataCallbackRoleCollectionTransformer(): Closure
    {
        return function ($roleCollectionSubmitted = []): CompanyRoleCollectionTransfer {
            $companyRoleCollectionTransfer = new CompanyRoleCollectionTransfer();

            foreach ($roleCollectionSubmitted as $role) {
                $companyRoleTransfer = (new CompanyRoleTransfer())
                    ->setIdCompanyRole($role);

                $companyRoleCollectionTransfer->addRole($companyRoleTransfer);
            }

            return $companyRoleCollectionTransfer;
        };
    }

    /**
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return static::TEMPLATE_PATH;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkCompanyField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_FK_COMPANY, HiddenType::class, [
            'required' => true,
        ]);

        return $this;
    }
}
