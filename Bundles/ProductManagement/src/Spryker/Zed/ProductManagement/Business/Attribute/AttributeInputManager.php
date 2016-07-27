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
     *
     * @return string
     */
    public function getSymfonyInputType($inputType)
    {
        $inputType = strtolower($inputType);

        switch ($inputType) {
            case 'select2':
                return 'select2';

            case 'textarea':
                return 'textarea';

            default:
                return 'text';
        }
    }

}
