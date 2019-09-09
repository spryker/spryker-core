<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Group;

use Application\Module\Zed;
use Codeception\Event\TestEvent;
use Codeception\GroupObject;

class ZedLogin extends GroupObject
{
    /**
     * @var string
     */
    public static $group = 'ZedLogin';

    /**
     * @param \Codeception\Event\TestEvent $e
     *
     * @return void
     */
    public function _before(TestEvent $e)
    {
        $this->getZedModule()->amZed();
        $this->getZedModule()->amLoggedInUser();
    }

    /**
     * @return \Application\Module\Zed
     */
    protected function getZedModule()
    {
        return $this->getModule('\\' . Zed::class);
    }
}
