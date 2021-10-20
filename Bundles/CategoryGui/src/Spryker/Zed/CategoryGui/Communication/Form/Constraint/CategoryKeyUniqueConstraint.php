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
    /**
     * @var string
     */
    public const OPTION_CATEGORY_GUI_REPOSITORY = 'categoryGuiRepository';

    /**
     * @var string
     */
    public const OPTION_TRANSLATOR_FACADE = 'translatorFacade';

    /**
     * @var string
     */
    protected const PARAMETER_CATEGORY_KEY = '%categoryKey%';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'Category with key "%categoryKey%" already in use, please choose another one.';

    /**
     * @var \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface
     */
    protected $categoryGuiRepository;

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @return \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface
     */
    public function getCategoryGuiRepository(): CategoryGuiRepositoryInterface
    {
        return $this->categoryGuiRepository;
    }

    /**
     * @param string $categoryKey
     *
     * @return string
     */
    public function getMessage(string $categoryKey): string
    {
        return $this->translatorFacade->trans(
            static::ERROR_MESSAGE,
            [
                static::PARAMETER_CATEGORY_KEY => $categoryKey,
            ]
        );
    }
}
