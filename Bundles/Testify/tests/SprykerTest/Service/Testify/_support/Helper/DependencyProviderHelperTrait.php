<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Testify\Helper;

use Codeception\Module;

trait DependencyProviderHelperTrait
{
    /**
     * @return \SprykerTest\Service\Testify\Helper\DependencyProviderHelper
     */
    protected function getDependencyProviderHelper(): DependencyProviderHelper
    {
        /** @var \SprykerTest\Service\Testify\Helper\DependencyProviderHelper $dependencyProviderHelper */
        $dependencyProviderHelper = $this->getModule('\\' . DependencyProviderHelper::class);

        return $dependencyProviderHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule(string $name): Module;
}
