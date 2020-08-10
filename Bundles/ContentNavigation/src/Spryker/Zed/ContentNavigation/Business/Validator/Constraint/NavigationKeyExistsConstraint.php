<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigation\Business\Validator\Constraint;

use Spryker\Zed\ContentNavigation\Dependency\Facade\ContentNavigationToNavigationFacadeInterface;
use Symfony\Component\Validator\Constraint;

class NavigationKeyExistsConstraint extends Constraint
{
    public const OPTION_NAVIGATION_FACADE = 'navigationFacade';

    protected const MESSAGE = 'Provided navigation key `%s` does not exist.';

    /**
     * @var \Spryker\Zed\ContentNavigation\Dependency\Facade\ContentNavigationToNavigationFacadeInterface
     */
    protected $navigationFacade;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::MESSAGE;
    }

    /**
     * @return \Spryker\Zed\ContentNavigation\Dependency\Facade\ContentNavigationToNavigationFacadeInterface
     */
    public function getNavigationFacade(): ContentNavigationToNavigationFacadeInterface
    {
        return $this->navigationFacade;
    }
}
