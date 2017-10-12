<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Dependency\Messenger;

class KernelToMessengerBridge implements KernelToMessengerInterface
{
    /**
     * @var \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected $messenger;

    /**
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $messengerFacade
     */
    public function __construct($messengerFacade)
    {
        $this->messenger = $messengerFacade;
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addSuccessMessage($message)
    {
        $this->messenger->addSuccessMessage($message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addInfoMessage($message)
    {
        $this->messenger->addInfoMessage($message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addErrorMessage($message)
    {
        $this->messenger->addErrorMessage($message);
    }
}
