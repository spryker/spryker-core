<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Exception\ModuleException;
use Codeception\Module;

trait ModuleLocatorTrait
{
    /**
     * @return \Codeception\Module[]
     */
    abstract protected function getModules();

    /**
     * @param string $className
     *
     * @throws \Codeception\Exception\ModuleException
     *
     * @return \Codeception\Module
     */
    public function locateModule(string $className): Module
    {
        $module = $this->findModule($className);

        if ($module === null) {
            throw new ModuleException(self::class, sprintf('The module requires %s', $className));
        }

        return $module;
    }

    /**
     * @param string $className
     *
     * @return \Codeception\Module|null
     */
    public function findModule(string $className): ?Module
    {
        foreach ($this->getModules() as $module) {
            if (is_a($module, $className)) {
                return $module;
            }
        }

        return null;
    }
}
