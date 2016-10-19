<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Form\Constraints;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @deprecated Will be removed with the next major release
 */
class CategoryFieldNotBlank extends NotBlank
{

    /**
     * @var string
     */
    public $message = "Please select a Category or check 'Delete subcategories'";

    /**
     * @var string
     */
    protected $categoryFieldName;

    /**
     * @var string
     */
    protected $checkboxFieldName;

    /**
     * @return string
     */
    public function getCategoryFieldName()
    {
        return $this->categoryFieldName;
    }

    /**
     * @return string
     */
    public function getCheckboxFieldName()
    {
        return $this->checkboxFieldName;
    }

}
