<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Installer\Business\Model;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

abstract class AbstractInstaller extends AbstractLogger
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $messenger;

    /**
     * @return void
     */
    abstract public function install();

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return $this
     */
    public function setMessenger(LoggerInterface $messenger)
    {
        $this->messenger = $messenger;

        return $this;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        $this->messenger->log($level, $message, $context);
    }

}
