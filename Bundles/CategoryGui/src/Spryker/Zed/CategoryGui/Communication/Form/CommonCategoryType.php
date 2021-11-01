<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryGui\CategoryGuiConfig getConfig()
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface getRepository()
 */
abstract class CommonCategoryType extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_CATEGORY_TEMPLATE_CHOICES = 'category_template_choices';

    /**
     * @var string
     */
    public const OPTION_DATA_CLASS = 'data_class';

    /**
     * @var string
     */
    public const FIELD_STORE_RELATION = 'store_relation';

    /**
     * @var string
     */
    protected const FIELD_CATEGORY_KEY = 'category_key';

    /**
     * @var string
     */
    protected const FIELD_IS_ACTIVE = 'is_active';

    /**
     * @var string
     */
    protected const FIELD_IS_IN_MENU = 'is_in_menu';

    /**
     * @var string
     */
    protected const FIELD_IS_CLICKABLE = 'is_clickable';

    /**
     * @var string
     */
    protected const FIELD_IS_SEARCHABLE = 'is_searchable';

    /**
     * @var string
     */
    protected const FIELD_IS_MAIN = 'is_main';

    /**
     * @var string
     */
    protected const FIELD_TEMPLATE = 'fk_category_template';

    /**
     * @var string
     */
    protected const FIELD_LOCALIZED_ATTRIBUTES = 'localized_attributes';

    /**
     * @var string
     */
    protected const LABEL_IS_ACTIVE = 'Active';

    /**
     * @var string
     */
    protected const LABEL_IS_IN_MENU = 'Visible in the category tree';

    /**
     * @var string
     */
    protected const LABEL_IS_SEARCHABLE = 'Allow to search for this category';

    /**
     * @var string
     */
    protected const LABEL_TEMPLATE = 'Template';

    /**
     * @var string
     */
    protected const BLOCK_PREFIX = 'category';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_CATEGORY_TEMPLATE_CHOICES);
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
            ->addCategoryKeyField($builder)
            ->addStoreRelationForm($builder, $options)
            ->addTemplateField($builder, $options)
            ->addIsActiveField($builder)
            ->addIsInMenuField($builder)
            ->addIsSearchableField($builder)
            ->addFormPlugins($builder)
            ->addLocalizedAttributesForm($builder);
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
                $this->getFactory()->createCategoryKeyUniqueConstraint(),
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
            'label' => static::LABEL_IS_ACTIVE,
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
            'label' => static::LABEL_IS_IN_MENU,
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
            'label' => static::LABEL_IS_SEARCHABLE,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addTemplateField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_TEMPLATE, Select2ComboBoxType::class, [
            'label' => static::LABEL_TEMPLATE,
            'choices' => array_flip($options[static::OPTION_CATEGORY_TEMPLATE_CHOICES]),
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
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addStoreRelationForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_STORE_RELATION,
            $this->getFactory()->getStoreRelationFormTypePlugin()->getType(),
            [
                'label' => false,
                'required' => false,
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFormPlugins(FormBuilderInterface $builder)
    {
        foreach ($this->getFactory()->getCategoryFormPlugins() as $categoryFormPlugin) {
            $categoryFormPlugin->buildForm($builder);
        }

        return $this;
    }
}
