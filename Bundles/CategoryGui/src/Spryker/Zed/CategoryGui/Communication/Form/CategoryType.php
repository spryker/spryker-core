<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryGui\CategoryGuiConfig getConfig()
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface getRepository()
 */
class CategoryType extends CommonCategoryType
{
    public const FIELD_PARENT_CATEGORY_NODE = 'parent_category_node';
    public const FIELD_EXTRA_PARENTS = 'extra_parents';

    public const OPTION_PARENT_CATEGORY_NODE_CHOICES = 'parent_category_node_choices';
    public const OPTION_INACTIVE_CHOICES = 'inactive_choices';

    public const OPTION_ATTRIBUTE_ACTION_URL = 'action_url';
    public const OPTION_ATTRIBUTE_ACTION_EVENT = 'action_event';
    public const OPTION_ATTRIBUTE_ACTION_FIELD = 'action_field';

    protected const LABEL_PARENT_CATEGORY_NODE = 'Parent';
    protected const LABEL_EXTRA_PARENTS = 'Additional Parents';

    protected const DEFAULT_ACTION_EVENT = 'change';
    protected const DEFAULT_ACTION_URL = '/category-gui/search/category-store';

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
            ->setDefaults([
                static::OPTION_ATTRIBUTE_ACTION_URL => static::DEFAULT_ACTION_URL,
                static::OPTION_ATTRIBUTE_ACTION_EVENT => static::DEFAULT_ACTION_EVENT,
                static::OPTION_ATTRIBUTE_ACTION_FIELD => static::FIELD_PARENT_CATEGORY_NODE,
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
        parent::buildForm($builder, $options);

        $this
            ->addParentNodeField($builder, $options[static::OPTION_PARENT_CATEGORY_NODE_CHOICES])
            ->addExtraParentsField($builder, $options[static::OPTION_PARENT_CATEGORY_NODE_CHOICES])
            ->addStoreRelationEventSubscriber($builder);
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
            'label' => static::LABEL_PARENT_CATEGORY_NODE,
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
            'label' => static::LABEL_EXTRA_PARENTS,
            'choices' => $choices,
            'choice_label' => 'name',
            'choice_value' => 'idCategoryNode',
            'multiple' => true,
            'group_by' => 'path',
            'required' => false,
        ]);

        $builder->get(static::FIELD_EXTRA_PARENTS)->addModelTransformer(
            $this->getFactory()->createCategoryExtraParentsTransformer()
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
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
                'required' => true,
                static::OPTION_ATTRIBUTE_ACTION_FIELD => $options[static::OPTION_ATTRIBUTE_ACTION_FIELD],
                static::OPTION_ATTRIBUTE_ACTION_URL => $options[static::OPTION_ATTRIBUTE_ACTION_URL],
                static::OPTION_ATTRIBUTE_ACTION_EVENT => $options[static::OPTION_ATTRIBUTE_ACTION_EVENT],
                'constraints' => [
                    new NotBlank(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStoreRelationEventSubscriber(FormBuilderInterface $builder)
    {
        $builder->addEventSubscriber($this->getFactory()->createCategoryStoreRelationFieldEventSubscriber());

        return $this;
    }
}
