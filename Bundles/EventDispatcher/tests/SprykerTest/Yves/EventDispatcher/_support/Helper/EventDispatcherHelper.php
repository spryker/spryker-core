<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\EventDispatcher\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin;
use SprykerTest\Yves\Testify\Helper\ApplicationHelper;

class EventDispatcherHelper extends Module
{
    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->getApplicationHelper()->addApplicationPlugin(new EventDispatcherApplicationPlugin());
    }

    /**
     * @return \SprykerTest\Yves\Testify\Helper\ApplicationHelper
     */
    protected function getApplicationHelper(): ApplicationHelper
    {
        /** @var \SprykerTest\Yves\Testify\Helper\ApplicationHelper $applicationHelper */
        $applicationHelper = $this->getModule('\\' . ApplicationHelper::class);

        return $applicationHelper;
    }
}
