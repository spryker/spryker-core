<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\Acl\Business\AclFacadeInterface getFacade()
 * @method \Spryker\Zed\Acl\Communication\AclCommunicationFactory getFactory()
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface getQueryContainer()
 */
class GroupForm extends AbstractType
{
    public const FIELD_TITLE = 'title';
    public const FIELD_ROLES = 'roles';

    public const OPTION_ROLE_CHOICES = 'role_choices';

    public const GROUP_UNIQUE_GROUP_CHECK = 'unique_group_check';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(self::OPTION_ROLE_CHOICES);

        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $defaultData = $form->getConfig()->getData();
                $submittedData = $form->getData();

                if (array_key_exists(self::FIELD_TITLE, $defaultData) === false ||
                    $defaultData[self::FIELD_TITLE] !== $submittedData[self::FIELD_TITLE]
                ) {
                    return [Constraint::DEFAULT_GROUP, self::GROUP_UNIQUE_GROUP_CHECK];
                }

                return [Constraint::DEFAULT_GROUP];
            },
        ]);
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
            ->addTitleField($builder)
            ->addRolesField($builder, $options[self::OPTION_ROLE_CHOICES]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTitleField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_TITLE, TextType::class, [
            'label' => 'Title',
            'constraints' => [
                new NotBlank(),
                new Callback([
                    'callback' => function ($name, ExecutionContextInterface $contextInterface) {
                        if ($this->getQueryContainer()->queryGroupByName($name)->count() > 0) {
                            $contextInterface->addViolation('Group name already in use');
                        }
                    },
                    'groups' => [self::GROUP_UNIQUE_GROUP_CHECK],
                ]),
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
    protected function addRolesField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(self::FIELD_ROLES, Select2ComboBoxType::class, [
            'label' => 'Assigned Roles',
            'placeholder' => false,
            'multiple' => true,
            'choices' => $choices,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
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

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'group';
    }
}
