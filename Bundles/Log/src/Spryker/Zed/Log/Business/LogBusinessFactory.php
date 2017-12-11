<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Log\Business\Model\LogClear;
use Spryker\Zed\Log\Business\Model\LogListener\LogListenerCollection;
use Spryker\Zed\Log\LogDependencyProvider;

/**
 * @method \Spryker\Zed\Log\LogConfig getConfig()
 */
class LogBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Log\Business\Model\LogClearInterface
     */
    public function createLogClearer()
    {
        return new LogClear(
            $this->getFilesystem(),
            $this->getConfig()->getLogFileDirectories()
        );
    }

    /**
     * @return \Spryker\Zed\Log\Business\Model\LogListener\LogListenerInterface
     */
    public function createLogListener()
    {
        return new LogListenerCollection($this->getProvidedDependency(LogDependencyProvider::LOG_LISTENERS));
    }

    /**
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    protected function getFilesystem()
    {
        return $this->getProvidedDependency(LogDependencyProvider::FILESYSTEM);
    }
}
