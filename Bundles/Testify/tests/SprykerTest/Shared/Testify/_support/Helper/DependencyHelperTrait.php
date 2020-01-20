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
    private function setDependency(string $key, $value): void
    {
        $this->getDependencyHelper()->setDependency($key, $value);
    }

    /**
     * @return \Codeception\Module|\SprykerTest\Shared\Testify\Helper\DependencyHelper
     */
    private function getDependencyHelper()
    {
        return $this->getModule('\\' . DependencyHelper::class);
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
