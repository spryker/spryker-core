<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\Acl\Business\AclFacadeInterface getFacade()
 * @method \Spryker\Zed\Acl\Communication\AclCommunicationFactory getFactory()
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface getQueryContainer()
 */
class RuleForm extends AbstractType
{
    public const OPTION_TYPE = 'option_type';

    public const FIELD_BUNDLE = 'bundle';
    public const FIELD_CONTROLLER = 'controller';
    public const FIELD_ACTION = 'action';
    public const FIELD_TYPE = 'type';
    public const FIELD_FK_ACL_ROLE = 'fk_acl_role';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(self::OPTION_TYPE);
    }

    /**
     * @deprecated Use `configureOptions()` instead.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addBundleField($builder)
            ->addControllerField($builder)
            ->addActionField($builder)
            ->addPermissionField($builder, $options[self::OPTION_TYPE])
            ->addRoleFkField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addBundleField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_BUNDLE, TextType::class, [
            'label' => 'Bundle',
            'constraints' => [
               new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addControllerField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_CONTROLLER, TextType::class, [
            'label' => 'Controller',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addActionField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ACTION, TextType::class, [
            'label' => 'Action',
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
    protected function addPermissionField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(self::FIELD_TYPE, ChoiceType::class, [
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
        $builder->add(self::FIELD_FK_ACL_ROLE, HiddenType::class, []);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'ruleset';
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
