<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Queue\Helper;

use Codeception\Module;

trait QueueHelperTrait
{
    /**
     * @return \SprykerTest\Client\Queue\Helper\QueueHelper
     */
    protected function getQueueHelper(): QueueHelper
    {
        /** @var \SprykerTest\Client\Queue\Helper\QueueHelper $queueHelper */
        $queueHelper = $this->getModule('\\' . QueueHelper::class);

        return $queueHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule(string $name): Module;
}
