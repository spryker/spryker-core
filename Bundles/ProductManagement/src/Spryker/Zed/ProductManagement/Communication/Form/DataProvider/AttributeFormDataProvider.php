<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Spryker\Zed\ProductManagement\Communication\Form\AttributeForm;

class AttributeFormDataProvider
{

    /**
     * @return array
     */
    public function getData()
    {
        return [
            AttributeForm::FIELD_VALUES => array_keys($this->getValues()),
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            AttributeForm::OPTION_ATTRIBUTE_TYPE_CHOICES => $this->getAttributeTypeChoices(),
            AttributeForm::OPTION_VALUES_CHOICES => $this->getValues(),
        ];
    }

    protected function getValues()
    {
        return [
            'foo' => 'Foo',
            'bar' => 'Bar',
            'lorem ipsum' => 'Lorem ipsum',
        ];
    }

    /**
     * @return array
     */
    protected function getAttributeTypeChoices()
    {
        // TODO: need to come from config
        return [
            'text' => 'text',
            'textarea' => 'textarea',
            'number' => 'number',
            'float' => 'float',
            'date' => 'date',
            'time' => 'time',
            'datetime' => 'datetime',
            'select' => 'select',
        ];
    }

}
