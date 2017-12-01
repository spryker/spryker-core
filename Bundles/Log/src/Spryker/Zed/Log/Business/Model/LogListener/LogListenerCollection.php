<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Business\Model\LogListener;

class LogListenerCollection implements LogListenerInterface
{
    /**
     * @var \Spryker\Zed\Log\Business\Model\LogListener\LogListenerInterface[]
     */
    protected $logListener;

    /**
     * @param array $logListener
     */
    public function __construct(array $logListener)
    {
        $this->logListener = $logListener;
    }

    /**
     * @return void
     */
    public function startListener()
    {
        foreach ($this->logListener as $logListener) {
            $logListener->startListener();
        }
    }

    /**
     * @return void
     */
    public function stopListener()
    {
        foreach ($this->logListener as $logListener) {
            $logListener->stopListener();
        }
    }
}
