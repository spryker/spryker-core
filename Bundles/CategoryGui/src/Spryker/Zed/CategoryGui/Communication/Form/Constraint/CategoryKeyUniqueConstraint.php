<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form\Constraint;

use Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface;
use Symfony\Component\Validator\Constraint;

class CategoryKeyUniqueConstraint extends Constraint
{
    public const OPTION_CATEGORY_GUI_REPOSITORY = 'categoryGuiRepository';

    /**
     * @var string
     */
    public $message = 'Category with key "%s" already in use, please choose another one.';

    /**
     * @var \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface
     */
    protected $categoryGuiRepository;

    /**
     * @return \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface
     */
    public function getCategoryGuiRepository(): CategoryGuiRepositoryInterface
    {
        return $this->categoryGuiRepository;
    }
}
