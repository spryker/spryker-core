<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\ProductCategory\Communication\Form\Constraints\CategoryFieldNotBlank;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryFormDelete extends CategoryFormEdit
{

    const DELETE_CHILDREN = 'delete_children';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addDeleteChildrenField($builder)
            ->addCategoryNodeField($builder, $options[self::OPTION_PARENT_CATEGORY_NODE_CHOICES])
            ->addPkCategoryNodeField($builder)
            ->addFkNodeCategoryField($builder);
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addDeleteChildrenField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::DELETE_CHILDREN, 'checkbox', [
                'label' => 'Delete subcategories',
            ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $choices
     *
     * @return self
     */
    protected function addCategoryNodeField(FormBuilderInterface $builder, array $choices)
    {
        $builder
            ->add(self::FIELD_FK_PARENT_CATEGORY_NODE, new Select2ComboBoxType(), [
                'label' => 'Or move them to category',
                'choices' => $choices,
                'multiple' => false,
                'constraints' => [
                    new CategoryFieldNotBlank([
                        'categoryFieldName' => self::FIELD_FK_PARENT_CATEGORY_NODE,
                        'checkboxFieldName' => self::DELETE_CHILDREN,
                    ]),
                ],
            ]);

        return $this;
    }

}
