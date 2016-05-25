<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryFormEdit extends CategoryFormAdd
{

    const FIELD_META_TITLE = 'meta_title';
    const FIELD_META_DESCRIPTION = 'meta_description';
    const FIELD_META_KEYWORDS = 'meta_keywords';
    const FIELD_CATEGORY_IMAGE_NAME = 'category_image_name';

    const CATEGORY_IS_ACTIVE = 'is_active';
    const CATEGORY_IS_IN_MENU = 'is_in_menu';
    const CATEGORY_IS_CLICKABLE = 'is_clickable';
    const CATEGORY_NODE_IS_MAIN = 'is_main';
    const CATEGORY_NODE_ORDER = 'node_order';

    const EXTRA_PARENTS = 'extra_parents';
    const LOCALIZED_ATTRIBUTES = 'localized_attributes';

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
            ->addCategoryIsActiveField($builder)
            ->addCategoryIsInMenuField($builder)
            ->addCategoryIsClickableField($builder)
            ->addCategoryNodeField($builder, $options[self::OPTION_PARENT_CATEGORY_NODE_CHOICES])
            ->addExtraParentsField($builder, $options[self::OPTION_PARENT_CATEGORY_NODE_CHOICES])
            ->addPkCategoryNodeField($builder)
            ->addFkNodeCategoryField($builder)
            ->addProductsToBeAssignedField($builder)
            ->addProductsToBeDeassignedField($builder)
            ->addProductsOrderField($builder)
            ->addProductCategoryPreconfigField($builder)
            ->addLocalizedAttributesForm($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMetaTitleField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_META_TITLE, 'text', [
                'label' => 'Meta Title',
                'required' => false,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMetaDescriptionField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_META_DESCRIPTION, 'textarea', [
                'label' => 'Meta Description',
                'required' => false,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMetaKeywordsField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_META_KEYWORDS, 'textarea', [
                'label' => 'Meta Keywords',
                'required' => false,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCategoryIsActiveField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::CATEGORY_IS_ACTIVE, 'checkbox', [
                'label' => 'Active',
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCategoryIsInMenuField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::CATEGORY_IS_IN_MENU, 'checkbox', [
                'label' => 'Show in Menu',
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCategoryIsClickableField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::CATEGORY_IS_CLICKABLE, 'hidden', [
                'label' => 'Clickable',
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
        $builder
            ->add(self::EXTRA_PARENTS, new Select2ComboBoxType(), [
                'label' => 'Additional Parents',
                'choices' => $choices,
                'multiple' => true,
                'required' => false,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkNodeCategoryField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_FK_NODE_CATEGORY, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductsToBeAssignedField(FormBuilderInterface $builder)
    {
        $builder
            ->add('products_to_be_assigned', 'hidden', [
                'attr' => [
                    'id' => 'products_to_be_assigned',
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductsToBeDeassignedField(FormBuilderInterface $builder)
    {
        $builder
            ->add('products_to_be_de_assigned', 'hidden', [
                'attr' => [
                    'id' => 'products_to_be_de_assigned',
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductsOrderField(FormBuilderInterface $builder)
    {
        $builder
            ->add('product_order', 'hidden', [
                'attr' => [
                    'id' => 'product_order',
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductCategoryPreconfigField(FormBuilderInterface $builder)
    {
        $builder
            ->add('product_category_preconfig', 'hidden', [
                'attr' => [
                    'id' => 'product_category_preconfig',
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCategoryKeyField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_CATEGORY_KEY, 'text', [
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
    protected function addCategoryNodeField(FormBuilderInterface $builder, array $choices)
    {
        $builder
            ->add(self::FIELD_FK_PARENT_CATEGORY_NODE, new Select2ComboBoxType(), [
                'label' => 'Parent',
                'choices' => $choices,
                'constraints' => [
                    new NotBlank(),
                ]
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPkCategoryNodeField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_PK_CATEGORY_NODE, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocalizedAttributesForm(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::LOCALIZED_ATTRIBUTES, 'collection', [
                'type' => new CategoryAttributeLocalizedForm()
            ]);

        return $this;
    }


}
