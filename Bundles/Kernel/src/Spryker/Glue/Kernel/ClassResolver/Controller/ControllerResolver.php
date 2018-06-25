<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\ClassResolver\Controller;

use Spryker\Shared\Kernel\ClassResolver\Controller\AbstractControllerResolver;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;

class ControllerResolver extends AbstractControllerResolver
{
    const CLASS_NAME_PATTERN = '\\%s\\Glue\\%s%s\\Controller\\%sController';

    /**
     * @return string
     */
    protected function getClassNamePattern()
    {
        return self::CLASS_NAME_PATTERN;
    }

    /**
     * @param \Spryker\Shared\Kernel\Communication\BundleControllerActionInterface $bundleControllerAction
     *
     * @return object|\Spryker\Glue\Kernel\Controller\AbstractController
     */
    public function resolve(BundleControllerActionInterface $bundleControllerAction)
    {
        return parent::resolve($bundleControllerAction);
    }
}
