<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Constraints;

use Symfony\Component\Validator\Constraints\NotBlank;

class AttributeFieldNotBlank extends NotBlank
{

    /**
     * @var string
     */
    public $message = "Please select an attribute and its value";

    /**
     * @var string
     */
    protected $attributeFieldValue;

    /**
     * @var string
     */
    protected $attributeCheckboxFieldName;

    /**
     * @return string
     */
    public function getAttributeFieldValue()
    {
        return $this->attributeFieldValue;
    }

    /**
     * @return string
     */
    public function getAttributeCheckboxFieldName()
    {
        return $this->attributeCheckboxFieldName;
    }

}
