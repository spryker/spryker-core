<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

trait DataCleanupHelperTrait
{
    /**
     * @return \SprykerTest\Shared\Testify\Helper\DataCleanupHelper
     */
    private function getDataCleanupHelper()
    {
        /** @var \SprykerTest\Shared\Testify\Helper\DataCleanupHelper $dataCleanerHelper */
        $dataCleanerHelper = $this->getModule('\\' . DataCleanupHelper::class);

        return $dataCleanerHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
