<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyUserRoleByCompany;

use Closure;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\CompanyRoleGui\CompanyRoleGuiConfig getConfig()
 * @method \Spryker\Zed\CompanyRoleGui\Communication\CompanyRoleGuiCommunicationFactory getFactory()
 */
class CompanyUserRoleByCompanyForm extends AbstractType
{
    public const OPTION_COMPANY_ROLE_CHOICES = 'company_role_choices';

    protected const FIELD_COMPANY_ROLE_COLLECTION = 'company_role_collection';

    protected const TEMPLATE_PATH = '@CompanyRoleGui/BusinessOnBehalfGui/company_role.twig';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCompanyRoleCollectionField($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_COMPANY_ROLE_CHOICES);
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return static::TEMPLATE_PATH;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCompanyRoleCollectionField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_COMPANY_ROLE_COLLECTION, ChoiceType::class, [
            'choices' => $options[static::OPTION_COMPANY_ROLE_CHOICES],
            'constraints' => $this->createCompanyRoleCollectionConstraints(),
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

            if (empty($roleCollection[CompanyRoleCollectionTransfer::ROLES])) {
                return $roles;
            }

            foreach ($roleCollection[CompanyRoleCollectionTransfer::ROLES] as $role) {
                $roles[] = $role[CompanyRoleTransfer::ID_COMPANY_ROLE];
            }

            return $roles;
        };
    }

    /**
     * @return \Closure
     */
    protected function getOutputDataCallbackRoleCollectionTransformer(): Closure
    {
        return function ($submittedRoleCollection = []): CompanyRoleCollectionTransfer {
            $companyRoleCollectionTransfer = new CompanyRoleCollectionTransfer();

            foreach ($submittedRoleCollection as $role) {
                $companyRoleTransfer = (new CompanyRoleTransfer())
                    ->setIdCompanyRole($role);

                $companyRoleCollectionTransfer->addRole($companyRoleTransfer);
            }

            return $companyRoleCollectionTransfer;
        };
    }
}
