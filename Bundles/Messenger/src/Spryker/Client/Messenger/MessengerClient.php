<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Messenger;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\Messenger\MessengerConfig;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

/**
 * @method \Spryker\Client\Messenger\MessengerFactory getFactory()
 */
class MessengerClient extends AbstractClient implements MessengerClientInterface
{
    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
        /** @var \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface $flashBag */
        $flashBag = $this->getFlashBag();
        $flashBag->add($key, $value);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface|\Symfony\Component\HttpFoundation\Session\SessionBagInterface
     */
    protected function getFlashBag()
    {
        return $this->getFactory()->getSessionClient()->getBag($this->getFlashBagName());
    }

    /**
     * @return string
     */
    protected function getFlashBagName()
    {
        return (new FlashBag())->getName();
    }
}
