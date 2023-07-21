<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Helper;

use Codeception\Module;

trait MessageBrokerHelperTrait
{
    /**
     * @return \SprykerTest\Zed\MessageBroker\Helper\InMemoryMessageBrokerHelper
     */
    protected function getInMemoryMessageBrokerHelper(): InMemoryMessageBrokerHelper
    {
        /** @var \SprykerTest\Zed\MessageBroker\Helper\InMemoryMessageBrokerHelper $inMemoryMessageBrokerHelper */
        $inMemoryMessageBrokerHelper = $this->getModule('\\' . InMemoryMessageBrokerHelper::class);

        return $inMemoryMessageBrokerHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule(string $name): Module;
}
