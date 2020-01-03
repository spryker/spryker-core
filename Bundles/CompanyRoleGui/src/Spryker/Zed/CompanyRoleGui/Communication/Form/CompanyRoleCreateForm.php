<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Form;

use Closure;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\CompanyRoleGui\CompanyRoleGuiConfig getConfig()
 * @method \Spryker\Zed\CompanyRoleGui\Communication\CompanyRoleGuiCommunicationFactory getFactory()
 */
class CompanyRoleCreateForm extends AbstractType
{
    public const OPTION_COMPANY_CHOICES = 'company_choices';
    public const OPTION_PERMISSION_CHOICES = 'permission_choices';

    protected const FIELD_ID_COMPANY_ROLE = 'idCompanyRole';
    protected const FIELD_FK_COMPANY = 'fkCompany';
    protected const FIELD_NAME = 'name';
    protected const FIELD_IS_DEFAULT = 'isDefault';
    protected const FIELD_PERMISSION_COLLECTION = 'permissionCollection';
    protected const FIELD_COMPANY_USER_COLLECTION = 'companyUserCollection';

    protected const TEMPLATE_PATH = '@CompanyRoleGui/_partials/company_role_manage_permissions.twig';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_COMPANY_CHOICES,
            static::OPTION_PERMISSION_CHOICES,
        ]);
        $resolver->setDefaults([
            'data_class' => CompanyRoleTransfer::class,
            'label' => false,
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
        $this->addIdCompanyRoleField($builder)
            ->addFkCompanyField($builder, $options)
            ->addNameField($builder)
            ->addIsDefaultField($builder)
            ->addPermissionCollectionField($builder, $options)
            ->addCompanyUserCollectionField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCompanyRoleField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_COMPANY_ROLE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addFkCompanyField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_FK_COMPANY, ChoiceType::class, [
            'choices' => $options[static::OPTION_COMPANY_CHOICES],
            'expanded' => false,
            'placeholder' => 'Select company',
            'label' => 'Company',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => 'Name',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsDefaultField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_DEFAULT, CheckboxType::class, [
            'label' => 'Is Default',
            'required' => false,
        ]);

        $this->disableIsDefaultFieldWhenChecked($builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function disableIsDefaultFieldWhenChecked(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer */
            $companyRoleTransfer = $event->getData();
            $form = $event->getForm();

            if ($companyRoleTransfer->getIsDefault()) {
                $config = $form->get(static::FIELD_IS_DEFAULT)->getConfig();
                $options = $config->getOptions();
                $options['disabled'] = true;
                $form->add(static::FIELD_IS_DEFAULT, CheckboxType::class, $options);
            }
        });
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addPermissionCollectionField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_PERMISSION_COLLECTION, ChoiceType::class, [
            'choices' => $options[static::OPTION_PERMISSION_CHOICES],
            'label' => false,
            'expanded' => true,
            'required' => true,
            'multiple' => true,
            'attr' => [
                'template_path' => $this->getTemplatePath(),
            ],
        ]);

        $callbackTransformer = new CallbackTransformer(
            $this->getInputDataCallbackPermissionCollectionTransformer(),
            $this->getOutputDataCallbackPermissionCollectionTransformer()
        );

        $builder->get(static::FIELD_PERMISSION_COLLECTION)
            ->addModelTransformer($callbackTransformer);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCompanyUserCollectionField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_COMPANY_USER_COLLECTION, HiddenType::class);

        return $this;
    }

    /**
     * @return \Closure
     */
    protected function getInputDataCallbackPermissionCollectionTransformer(): Closure
    {
        return function ($permissionCollection = []): array {
            $permissions = [];
            if (!empty($permissionCollection[PermissionCollectionTransfer::PERMISSIONS])) {
                foreach ($permissionCollection[PermissionCollectionTransfer::PERMISSIONS] as $permission) {
                    $permissions[] = $permission[PermissionTransfer::ID_PERMISSION];
                }
            }

            return $permissions;
        };
    }

    /**
     * @return \Closure
     */
    protected function getOutputDataCallbackPermissionCollectionTransformer(): Closure
    {
        return function ($permissionCollectionSubmitted = []): PermissionCollectionTransfer {
            $permissionCollectionTransfer = new PermissionCollectionTransfer();
            foreach ($permissionCollectionSubmitted as $permission) {
                $permissionTransfer = (new PermissionTransfer())
                    ->setIdPermission($permission);
                $permissionCollectionTransfer->addPermission($permissionTransfer);
            }

            return $permissionCollectionTransfer;
        };
    }

    /**
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return static::TEMPLATE_PATH;
    }
}
