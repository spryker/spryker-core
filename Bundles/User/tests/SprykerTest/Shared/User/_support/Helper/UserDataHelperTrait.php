<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\User\Helper;

trait UserDataHelperTrait
{
    /**
     * @return \SprykerTest\Shared\User\Helper\UserDataHelper
     */
    private function getUserDataHelper()
    {
        return $this->getModule('\\' . UserDataHelper::class);
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
