<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Form;

use ArrayObject;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryType extends AbstractType
{

    const OPTION_PARENT_CATEGORY_NODE_CHOICES = 'parent_category_node_choices';
    const OPTION_CATEGORY_TEMPLATE_CHOICES = 'category_template_choices';

    const FIELD_CATEGORY_KEY = 'category_key';
    const FIELD_IS_ACTIVE = 'is_active';
    const FIELD_IS_IN_MENU = 'is_in_menu';
    const FIELD_IS_CLICKABLE = 'is_clickable';
    const FIELD_IS_SEARCHABLE = 'is_searchable';
    const FIELD_IS_MAIN = 'is_main';
    const FIELD_PARENT_CATEGORY_NODE = 'parent_category_node';
    const FIELD_EXTRA_PARENTS = 'extra_parents';
    const FIELD_TEMPLATE = 'fk_category_template';

    const FIELD_LOCALIZED_ATTRIBUTES = 'localized_attributes';

    /**
     * @var \Spryker\Zed\Category\Dependency\Plugin\CategoryFormPluginInterface[]
     */
    protected $formPlugins = [];

    /**
     * @return string
     */
    public function getName()
    {
        return 'category';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_PARENT_CATEGORY_NODE_CHOICES);
        $resolver->setRequired(static::OPTION_CATEGORY_TEMPLATE_CHOICES);
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
            ->addIsClickableField($builder)
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
        $builder->add(static::FIELD_CATEGORY_KEY, 'text', [
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
    protected function addIsActiveField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_ACTIVE, 'checkbox', [
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
        $builder->add(static::FIELD_IS_IN_MENU, 'checkbox', [
            'label' => 'Show in Menu',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsClickableField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_CLICKABLE, 'checkbox', [
            'label' => 'Clickable',
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
        $builder->add(static::FIELD_IS_SEARCHABLE, 'checkbox', [
            'label' => 'Searchable',
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
        $builder->add(static::FIELD_PARENT_CATEGORY_NODE, new Select2ComboBoxType(), [
            'property_path' => 'parentCategoryNode',
            'label' => 'Parent',
            'choices' => $choices,
            'choices_as_values' => true,
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
        $builder->add(self::FIELD_EXTRA_PARENTS, new Select2ComboBoxType(), [
            'label' => 'Additional Parents',
            'choices' => $choices,
            'choices_as_values' => true,
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
        $builder->add(static::FIELD_TEMPLATE, new Select2ComboBoxType(), [
            'label' => 'Template',
            'choices' => $choices,
            'required' => false,
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
        $builder->add(static::FIELD_LOCALIZED_ATTRIBUTES, 'collection', [
            'type' => new CategoryLocalizedAttributeType(),
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
        foreach ($this->formPlugins as $formPlugin) {
            $formPlugin->buildForm($builder);
        }

        return $this;
    }

}
