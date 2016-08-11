<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageForm;
use Symfony\Component\Form\FormBuilderInterface;

class ProductFormEdit extends ProductFormAdd
{

    /**
     * @return array
     */
    protected function getValidationGroups()
    {
        $validationGroups = parent::getValidationGroups();

        return array_filter($validationGroups, function($item){
            return $item !== ImageForm::VALIDATION_GROUP_IMAGE_COLLECTION;
        });
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ProductFormEdit';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAttributeVariantForm(FormBuilderInterface $builder, array $options = [])
    {
        return $this;
    }

}
