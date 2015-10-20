<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Communication\Form\Constraints;

use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryFieldNotBlank extends NotBlank
{

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
