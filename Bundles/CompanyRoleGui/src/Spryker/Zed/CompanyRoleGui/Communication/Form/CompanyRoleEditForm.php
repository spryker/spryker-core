<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Form;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyRoleEditForm extends AbstractType
{
    public const OPTION_COMPANIES = 'companies';
    public const OPTION_COMPANY_ROLE_PERMISSIONS = 'companyRolePermissions';

    public const FIELD_ID_COMPANY_ROLE = 'idCompanyRole';
    public const FIELD_FK_COMPANY = 'fkCompany';
    public const FIELD_NAME = 'name';
    public const FIELD_IS_DEFAULT = 'isDefault';
    public const FIELD_PERMISSION_COLLECTION = 'permissionCollection';
    public const FIELD_COMPANY_USER_COLLECTION = 'companyUserCollection';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_COMPANIES)
            ->setRequired(static::OPTION_COMPANY_ROLE_PERMISSIONS)
            ->setDefaults([
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
    public function buildForm(FormBuilderInterface $builder, array $options)
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
    protected function addIdCompanyRoleField(FormBuilderInterface $builder): self
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
    protected function addFkCompanyField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_FK_COMPANY, ChoiceType::class, [
            'choices' => array_flip($options[static::OPTION_COMPANIES]),
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
    protected function addNameField(FormBuilderInterface $builder): self
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
    protected function addIsDefaultField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_IS_DEFAULT, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addPermissionCollectionField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_PERMISSION_COLLECTION, ChoiceType::class, [
            'choices' => array_flip($options[static::OPTION_COMPANY_ROLE_PERMISSIONS]),
            'expanded' => true,
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCompanyUserCollectionField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_COMPANY_USER_COLLECTION, HiddenType::class);

        return $this;
    }
}
