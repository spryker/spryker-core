<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

trait ClassHelperTrait
{
    /**
     * @return \SprykerTest\Shared\Testify\Helper\ClassHelper
     */
    private function getClassHelper(): ClassHelper
    {
        /** @var \SprykerTest\Shared\Testify\Helper\ClassHelper $classHelper */
        $classHelper = $this->getModule('\\' . ClassHelper::class);

        return $classHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
