<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Messenger\FlashBag;

use Spryker\Client\Messenger\Dependency\Client\MessengerToSessionClientInterface;
use Spryker\Shared\Messenger\MessengerConfig;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag as SymfonyFlashBag;

class FlashBag implements FlashBagInterface
{
    /**
     * @var \Spryker\Client\Messenger\Dependency\Client\MessengerToSessionClientInterface
     */
    protected $sessionClient;

    /**
     * FlashBag constructor.
     *
     * @param \Spryker\Client\Messenger\Dependency\Client\MessengerToSessionClientInterface $sessionClient
     */
    public function __construct(
        MessengerToSessionClientInterface $sessionClient
    ) {
        $this->sessionClient = $sessionClient;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $message
     *
     * @return void
     */
    public function addSuccessMessage($message)
    {
        $this->addToFlashBag(MessengerConfig::FLASH_MESSAGES_SUCCESS, $message);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $message
     *
     * @return void
     */
    public function addInfoMessage($message)
    {
        $this->addToFlashBag(MessengerConfig::FLASH_MESSAGES_INFO, $message);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $message
     *
     * @return void
     */
    public function addErrorMessage($message)
    {
        $this->addToFlashBag(MessengerConfig::FLASH_MESSAGES_ERROR, $message);
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    protected function addToFlashBag($key, $value)
    {
        $this->getFlashBag()->add($key, $value);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface|\Symfony\Component\HttpFoundation\Session\SessionBagInterface
     */
    protected function getFlashBag()
    {
        return $this->sessionClient->getBag($this->getFlashBagName());
    }

    /**
     * @return string
     */
    protected function getFlashBagName()
    {
        return (new SymfonyFlashBag())->getName();
    }
}
