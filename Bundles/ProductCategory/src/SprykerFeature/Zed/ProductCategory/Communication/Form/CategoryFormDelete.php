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
                'label' => 'Or move them to category',
                'choices' => $this->getCategoriesWithPaths(),
                'multiple' => false,
            ])
            ->addHidden(self::PK_CATEGORY_NODE)
            ->addHidden(self::FK_NODE_CATEGORY)
        ;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $fields = parent::populateFormFields();
        $fields[self::FK_PARENT_CATEGORY_NODE] = null;

        return $fields;
    }
}
