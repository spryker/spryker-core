<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

trait DependencyHelperTrait
{
    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    protected function setDependency(string $key, $value): void
    {
        $this->getDependencyHelper()->setDependency($key, $value);
    }

    /**
     * @return \SprykerTest\Shared\Testify\Helper\DependencyHelper
     */
    protected function getDependencyHelper(): DependencyHelper
    {
        /** @var \SprykerTest\Shared\Testify\Helper\DependencyHelper $dependencyHelper */
        $dependencyHelper = $this->getModule('\\' . DependencyHelper::class);

        return $dependencyHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
