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
    /**
     * @var string
     */
    public const OPTION_CATEGORY_FACADE = 'categoryFacade';

    /**
     * @var string
     */
    public const OPTION_TRANSLATOR_FACADE = 'translatorFacade';

    /**
     * @var string
     */
    protected const PARAMETER_CATEGORY_NAME = '%categoryName%';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'Category with name "%categoryName%" already in use in this category level, please choose another one.';

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @return \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface
     */
    public function getCategoryFacade(): CategoryGuiToCategoryFacadeInterface
    {
        return $this->categoryFacade;
    }

    /**
     * @param string $categoryName
     *
     * @return string
     */
    public function getMessage(string $categoryName): string
    {
        return $this->translatorFacade->trans(
            static::ERROR_MESSAGE,
            [
                static::PARAMETER_CATEGORY_NAME => $categoryName,
            ]
        );
    }
}
