<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\Acl\Business\AclFacadeInterface getFacade()
 * @method \Spryker\Zed\Acl\Communication\AclCommunicationFactory getFactory()
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Acl\AclConfig getConfig()
 * @method \Spryker\Zed\Acl\Persistence\AclRepositoryInterface getRepository()
 */
class RuleForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_TYPE = 'option_type';

    /**
     * @var string
     */
    public const FIELD_BUNDLE = 'bundle';

    /**
     * @var string
     */
    public const FIELD_CONTROLLER = 'controller';

    /**
     * @var string
     */
    public const FIELD_ACTION = 'action';

    /**
     * @var string
     */
    public const FIELD_TYPE = 'type';

    /**
     * @var string
     */
    public const FIELD_FK_ACL_ROLE = 'fk_acl_role';

    /**
     * @var string
     */
    public const BUNDLE_FIELD_CHOICES = 'bundle_field_choices';

    /**
     * @var string
     */
    public const CONTROLLER_FIELD_CHOICES = 'controller_field_choices';

    /**
     * @var string
     */
    public const ACTION_FIELD_CHOICES = 'action_field_choices';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(static::OPTION_TYPE)
            ->setRequired(static::BUNDLE_FIELD_CHOICES)
            ->setRequired(static::CONTROLLER_FIELD_CHOICES)
            ->setRequired(static::ACTION_FIELD_CHOICES);
    }

    /**
     * @deprecated Use {@link configureOptions()} instead.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $this->configureOptions($resolver);
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
            ->addBundleField($builder, $options[static::BUNDLE_FIELD_CHOICES])
            ->addControllerField($builder, $options[static::CONTROLLER_FIELD_CHOICES])
            ->addActionField($builder, $options[static::ACTION_FIELD_CHOICES])
            ->addPermissionField($builder, $options[static::OPTION_TYPE])
            ->addRoleFkField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addBundleField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_BUNDLE, ChoiceType::class, [
            'label' => 'Bundle',
            'choices' => $choices,
            'expanded' => false,
            'multiple' => false,
            'required' => true,
            'placeholder' => 'Select a bundle',
            'constraints' => [
                new NotBlank(),
            ],
            'attr' => [
                'class' => 'js-select-dependable js-select-dependable--bundle spryker-form-select2combobox',
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
    protected function addControllerField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_CONTROLLER, ChoiceType::class, [
            'label' => 'Controller',
            'choices' => $choices,
            'expanded' => false,
            'multiple' => false,
            'required' => true,
            'placeholder' => 'Select a controller',
            'constraints' => [
                new NotBlank(),
            ],
            'attr' => [
                'class' => 'js-select-dependable js-select-dependable--controller spryker-form-select2combobox',
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
    protected function addActionField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_ACTION, ChoiceType::class, [
            'label' => 'Action',
            'choices' => $choices,
            'expanded' => false,
            'multiple' => false,
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
            'placeholder' => 'Select an action',
            'attr' => [
                'class' => 'js-select-dependable js-select-dependable--action spryker-form-select2combobox',
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
    protected function addPermissionField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_TYPE, ChoiceType::class, [
            'label' => 'Permission',
            'choices' => $choices,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addRoleFkField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_ACL_ROLE, HiddenType::class, []);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'ruleset';
    }

    /**
     * @deprecated Use {@link getBlockPrefix()} instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
