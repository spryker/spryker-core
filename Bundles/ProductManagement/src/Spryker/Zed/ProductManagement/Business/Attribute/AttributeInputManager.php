<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AttributeInputManager
{
    /**
     * @param string $inputType
     * @param string|null $value
     *
     * @return string
     */
    public function getSymfonyInputType($inputType, $value = null)
    {
        $inputType = strtolower($inputType);
        $useTextArea = mb_strlen($value) > 255;

        $input = 'text';

        switch ($inputType) {
            case 'textarea':
                $input = TextareaType::class;

                break;
            case 'select2':
                $input = Select2ComboBoxType::class;

                break;
        }

        if ($useTextArea) {
            return TextareaType::class;
        }

        return $input;
    }
}
