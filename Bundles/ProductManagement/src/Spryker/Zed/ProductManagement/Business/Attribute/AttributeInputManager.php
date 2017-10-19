<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

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
                $input = 'textarea';
                break;

            case 'select2':
                $input = 'select2';
                break;
        }

        if ($useTextArea) {
            return 'textarea';
        }

        return $input;
    }
}
