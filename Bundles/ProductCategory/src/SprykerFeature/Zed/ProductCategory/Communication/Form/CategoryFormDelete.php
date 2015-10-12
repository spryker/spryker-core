<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Communication\Form;

use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryFormDelete extends CategoryFormEdit
{
    const DELETE_CHILDREN = 'delete_children';

    /**
     * @return CategoryFormDelete
     */
    protected function buildFormFields()
    {
        return $this->addCheckbox(self::DELETE_CHILDREN, [
                'label' => 'Delete subcategories',
            ])
            ->addSelect2ComboBox(self::FK_PARENT_CATEGORY_NODE, [
                'label' => 'Or move them to',
                'choices' => $this->getCategories(),
                'constraints' => [
                    new NotBlank(),
                ],
                'multiple' => false,
            ])
            ->addHidden(self::PK_CATEGORY_NODE)
            ->addHidden(self::FK_NODE_CATEGORY)
        ;
    }
}
