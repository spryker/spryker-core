<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductFormAttributeVariant extends ProductFormAttributeAbstract
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder, array $options = [])
    {
        $name = $builder->getName();
        $attributes = $options[self::OPTION_ATTRIBUTE];
        $config = $this->getValueFieldConfig($name, $attributes);

        $allowInput = $attributes[$name][self::ALLOW_INPUT];

        $input = new Select2ComboBoxType();
        $config['multiple'] = true; //be able to select multiple values of super attributes;
        $config['attr']['style'] .= ' width: 250px';
        $config['choices'] = [];
        $config['attr']['class'] .= ' ajax';

        if ($allowInput) {
            $config['attr']['tags'] = true;
        }

        $builder->add(self::FIELD_VALUE, $input, $config);

        return $this;
    }

}
