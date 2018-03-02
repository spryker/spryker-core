<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Messenger;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Messenger\MessengerFactory getFactory()
 */
class MessengerClient extends AbstractClient implements MessengerClientInterface
{
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
        $this->getFactory()
            ->createFlashBag()
            ->addSuccessMessage($message);
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
        $this->getFactory()
            ->createFlashBag()
            ->addInfoMessage($message);
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
        $this->getFactory()
            ->createFlashBag()
            ->addErrorMessage($message);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function processFlashMessagesFromLastZedRequest(): void
    {
        $this->getFactory()
            ->createZedRequestMessages()
            ->processFlashMessagesFromLastZedRequest();
    }
}
