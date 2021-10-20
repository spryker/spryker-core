<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Form;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyRoleGui\Communication\Form\FormType\CompanyRoleChoiceType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\CompanyRoleGui\CompanyRoleGuiConfig getConfig()
 * @method \Spryker\Zed\CompanyRoleGui\Communication\CompanyRoleGuiCommunicationFactory getFactory()
 */
class CompanyUserRoleForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_VALUES_ROLES_CHOICES = 'company_role_choices';

    /**
     * @var string
     */
    public const OPTION_ATTRIBUTES_ROLES_CHOICES = 'company_role_attributes';

    /**
     * @uses \Spryker\Zed\CompanyUserGui\Communication\Form\CompanyUserForm
     * @var string
     */
    protected const FIELD_FK_COMPANY = 'fk_company';

    /**
     * @var string
     */
    protected const FIELD_COMPANY_ROLE_COLLECTION = 'company_role_collection';

    /**
     * @var string
     */
    protected const TEMPLATE_PATH = '@CompanyRoleGui/CompanyUser/company_role.twig';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCompanyRoleCollectionField($builder, $options);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'companyRolesSearchPreSubmitHandler']);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_VALUES_ROLES_CHOICES);
        $resolver->setRequired(static::OPTION_ATTRIBUTES_ROLES_CHOICES);
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return static::TEMPLATE_PATH;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'company-user';
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $formEvent
     *
     * @return void
     */
    public function companyRolesSearchPreSubmitHandler(FormEvent $formEvent): void
    {
        $data = $formEvent->getData();
        $form = $formEvent->getForm();

        if (
            !isset($data[static::FIELD_COMPANY_ROLE_COLLECTION]) ||
            !isset($data[static::FIELD_FK_COMPANY]) ||
            !$form->has(static::FIELD_COMPANY_ROLE_COLLECTION)
        ) {
            return;
        }

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setFkCompany($data[static::FIELD_FK_COMPANY]);

        $options = $this->getFactory()
            ->createCompanyUserRoleFormDataProvider()
            ->getOptions($companyUserTransfer);

        $form->add(
            static::FIELD_COMPANY_ROLE_COLLECTION,
            CompanyRoleChoiceType::class,
            $this->getCompanyRoleFieldParameters($options)
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCompanyRoleCollectionField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_COMPANY_ROLE_COLLECTION,
            CompanyRoleChoiceType::class,
            $this->getCompanyRoleFieldParameters($options)
        );

        return $this;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function getCompanyRoleFieldParameters(array $options): array
    {
        return [
            'choices' => $options[static::OPTION_VALUES_ROLES_CHOICES],
            'choice_attr' => $options[static::OPTION_ATTRIBUTES_ROLES_CHOICES],
            'constraints' => $this->createCompanyRoleCollectionConstraints(),
            'label' => false,
            'expanded' => true,
            'required' => true,
            'multiple' => true,
            'attr' => [
                'template_path' => $this->getTemplatePath(),
            ],
        ];
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
}
