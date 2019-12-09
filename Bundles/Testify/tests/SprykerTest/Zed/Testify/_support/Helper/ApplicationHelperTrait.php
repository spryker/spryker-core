<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Testify\Helper;

trait ApplicationHelperTrait
{
    /**
     * @return \SprykerTest\Zed\Testify\Helper\ApplicationHelper
     */
    protected function getApplicationHelper(): ApplicationHelper
    {
        /** @var \SprykerTest\Zed\Testify\Helper\ApplicationHelper $applicationHelper */
        $applicationHelper = $this->getModule('\\' . ApplicationHelper::class);

        return $applicationHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
