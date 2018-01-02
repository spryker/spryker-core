<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Messenger\FlashMessenger;

use Spryker\Shared\Messenger\MessengerConfig;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class FlashMessenger implements FlashMessengerInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface
     */
    protected $flashBag;

    /**
     * @param \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface $flashBag
     */
    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function addSuccessMessage($message)
    {
        $this->addToFlashBag(MessengerConfig::FLASH_MESSAGES_SUCCESS, $message);

        return $this;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function addInfoMessage($message)
    {
        $this->addToFlashBag(MessengerConfig::FLASH_MESSAGES_INFO, $message);

        return $this;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function addErrorMessage($message)
    {
        $this->addToFlashBag(MessengerConfig::FLASH_MESSAGES_ERROR, $message);

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    protected function addToFlashBag($key, $value)
    {
        $this->flashBag->add($key, $value);
    }
}
