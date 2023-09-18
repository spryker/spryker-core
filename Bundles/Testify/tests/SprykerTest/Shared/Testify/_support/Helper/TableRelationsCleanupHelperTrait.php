<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Module;

trait TableRelationsCleanupHelperTrait
{
    /**
     * @return \SprykerTest\Shared\Testify\Helper\TableRelationsCleanupHelper
     */
    protected function getTableRelationsCleanupHelper(): TableRelationsCleanupHelper
    {
        /** @var \SprykerTest\Shared\Testify\Helper\TableRelationsCleanupHelper $tableRelationsCleanupHelper */
        $tableRelationsCleanupHelper = $this->getModule('\\' . TableRelationsCleanupHelper::class);

        return $tableRelationsCleanupHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule(string $name): Module;
}
