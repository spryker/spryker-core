<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\Helper;

trait DependencyProviderHelperTrait
{
    /**
     * @return \SprykerTest\Glue\Testify\Helper\DependencyProviderHelper
     */
    protected function getDependencyProviderHelper(): DependencyProviderHelper
    {
        /** @var \SprykerTest\Glue\Testify\Helper\DependencyProviderHelper $dependencyProviderHelper */
        $dependencyProviderHelper = $this->getModule('\\' . DependencyProviderHelper::class);

        return $dependencyProviderHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
