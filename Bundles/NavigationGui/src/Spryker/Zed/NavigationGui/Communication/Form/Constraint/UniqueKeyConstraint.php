<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueKeyConstraint extends SymfonyConstraint
{
    public const OPTION_NAVIGATION_GUI_REPOSITORY = 'navigationGuiRepository';

    protected const ERROR_MESSAGE = 'Navigation with the same key already exists.';

    /**
     * @var \Spryker\Zed\NavigationGui\Persistence\NavigationGuiRepositoryInterface
     */
    protected $navigationGuiRepository;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::ERROR_MESSAGE;
    }

    /**
     * @return string
     */
    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasKey(string $key): bool
    {
        return $this->navigationGuiRepository->hasNavigationKey($key);
    }
}
