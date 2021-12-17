<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\Backend;

use Spryker\Glue\Kernel\Container as GlueContainer;

class Container extends GlueContainer
{
    /**
     * @return \Spryker\Glue\Kernel\Backend\Locator&\Generated\GlueBackend\Ide\AutoCompletion&\Spryker\Shared\Kernel\LocatorLocatorInterface
     */
    public function getLocator(): Locator
    {
        /** @var \Spryker\Glue\Kernel\Backend\Locator&\Generated\GlueBackend\Ide\AutoCompletion&\Spryker\Shared\Kernel\LocatorLocatorInterface $locator */
        $locator = Locator::getInstance();

        return $locator;
    }
}
