<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form;

use ArrayObject;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryGui\CategoryGuiConfig getConfig()
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface getRepository()
 */
class CategoryType extends AbstractType
{
    public const OPTION_PARENT_CATEGORY_NODE_CHOICES = 'parent_category_node_choices';
    public const OPTION_CATEGORY_TEMPLATE_CHOICES = 'category_template_choices';

    public const FIELD_CATEGORY_KEY = 'category_key';
    public const FIELD_IS_ACTIVE = 'is_active';
    public const FIELD_IS_IN_MENU = 'is_in_menu';
    public const FIELD_IS_CLICKABLE = 'is_clickable';
    public const FIELD_IS_SEARCHABLE = 'is_searchable';
    public const FIELD_IS_MAIN = 'is_main';
    public const FIELD_PARENT_CATEGORY_NODE = 'parent_category_node';
    public const FIELD_EXTRA_PARENTS = 'extra_parents';
    public const FIELD_TEMPLATE = 'fk_category_template';
    public const FIELD_LOCALIZED_ATTRIBUTES = 'localized_attributes';

    /**
     * @var \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryFormPluginInterface[]
     */
    protected $formPlugins = [];

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'category';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired(static::OPTION_PARENT_CATEGORY_NODE_CHOICES)
            ->setRequired(static::OPTION_CATEGORY_TEMPLATE_CHOICES);
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
            ->addCategoryKeyField($builder)
            ->addIsActiveField($builder)
            ->addIsInMenuField($builder)
            ->addIsSearchableField($builder)
            ->addParentNodeField($builder, $options[static::OPTION_PARENT_CATEGORY_NODE_CHOICES])
            ->addExtraParentsField($builder, $options[static::OPTION_PARENT_CATEGORY_NODE_CHOICES])
            ->addTemplateField($builder, $options[static::OPTION_CATEGORY_TEMPLATE_CHOICES])
            ->addPluginForms($builder)
            ->addLocalizedAttributesForm($builder);
    }

    /**
     * @param array $formPlugins
     *
     * @return void
     */
    public function setFormPlugins(array $formPlugins)
    {
        $this->formPlugins = $formPlugins;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCategoryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CATEGORY_KEY, TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Callback([
                    'callback' => $this->uniqueKeyValidateCallback(),
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsActiveField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_ACTIVE, CheckboxType::class, [
            'label' => 'Active',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsInMenuField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_IN_MENU, CheckboxType::class, [
            'label' => 'Visible in the category tree',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsSearchableField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_SEARCHABLE, CheckboxType::class, [
            'label' => 'Allow to search for this category',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addParentNodeField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_PARENT_CATEGORY_NODE, Select2ComboBoxType::class, [
            'property_path' => 'parentCategoryNode',
            'label' => 'Parent',
            'choices' => $choices,
            'choice_label' => 'name',
            'choice_value' => 'idCategoryNode',
            'group_by' => 'path',
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addExtraParentsField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_EXTRA_PARENTS, Select2ComboBoxType::class, [
            'label' => 'Additional Parents',
            'choices' => $choices,
            'choice_label' => 'name',
            'choice_value' => 'idCategoryNode',
            'multiple' => true,
            'group_by' => 'path',
            'required' => false,
        ]);

        $builder->get(static::FIELD_EXTRA_PARENTS)->addModelTransformer(new CallbackTransformer(
            function ($extraParents) {
                return (array)$extraParents;
            },
            function ($extraParents) {
                return new ArrayObject($extraParents);
            }
        ));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addTemplateField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_TEMPLATE, Select2ComboBoxType::class, [
            'label' => 'Template',
            'choices' => array_flip($choices),
            'required' => true,
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
    protected function addLocalizedAttributesForm(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_LOCALIZED_ATTRIBUTES, CollectionType::class, [
            'entry_type' => CategoryLocalizedAttributeType::class,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPluginForms(FormBuilderInterface $builder)
    {
        foreach ($this->getFactory()->getCategoryFormPlugins() as $formPlugin) {
            $formPlugin->buildForm($builder);
        }

        return $this;
    }

    /**
     * @return \Closure
     */
    protected function uniqueKeyValidateCallback(): callable
    {
        return function ($key, ExecutionContextInterface $context) {
            $data = $context->getRoot()->getData();

            if (!($data instanceof CategoryTransfer)) {
                return;
            }

            if ($this->getFactory()->getRepository()->isCategoryKeyUsed($key, $data->getIdCategory())) {
                $context->addViolation(sprintf('Category with key "%s" already in use, please choose another one.', $key));
            }
        };
    }
}
