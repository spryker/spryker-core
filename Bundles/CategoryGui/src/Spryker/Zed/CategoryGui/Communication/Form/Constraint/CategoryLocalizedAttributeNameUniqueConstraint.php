<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form\Constraint;

use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface;
use Symfony\Component\Validator\Constraint;

class CategoryLocalizedAttributeNameUniqueConstraint extends Constraint
{
    public const OPTION_CATEGORY_FACADE = 'categoryFacade';

    /**
     * @var string
     */
    public $message = 'Category with name "%s" already in use in this category level, please choose another one.';

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @return \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface
     */
    public function getCategoryFacade(): CategoryGuiToCategoryFacadeInterface
    {
        return $this->categoryFacade;
    }
}
