<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\ProductManagement\Communication\Form\Constraints\AttributeFieldNotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
        $isMultiple = $attributes[$name][self::MULTIPLE];

        $input = new Select2ComboBoxType();
        $config['multiple'] = $isMultiple;
        $config['choices'] = [
            'aaa', 'bbb', 'ccc'
        ];
        $config['tags'] = false;

        $builder->add(self::FIELD_VALUE, $input, $config);

        return $this;
    }

}
