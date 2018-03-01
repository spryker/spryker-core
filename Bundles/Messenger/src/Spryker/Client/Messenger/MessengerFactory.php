<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Messenger;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Messenger\Dependency\Client\MessengerToSessionClientInterface;
use Spryker\Client\Messenger\FlashBag\FlashBag;
use Spryker\Client\Messenger\FlashBag\FlashBagInterface;
use Spryker\Client\Messenger\ZedRequest\Messages;
use Spryker\Client\Messenger\ZedRequest\MessagesInterface;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class MessengerFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Messenger\ZedRequest\MessagesInterface
     */
    public function createZedRequestMessages(): MessagesInterface
    {
        return new Messages(
            $this->getZedClient(),
            $this->createFlashBag()
        );
    }

    /**
     * @return \Spryker\Client\Messenger\FlashBag\FlashBagInterface
     */
    public function createFlashBag(): FlashBagInterface
    {
        return new FlashBag(
            $this->getSessionClient()
        );
    }

    /**
     * @return \Spryker\Client\Messenger\Dependency\Client\MessengerToSessionClientInterface
     */
    protected function getSessionClient(): MessengerToSessionClientInterface
    {
        return $this->getProvidedDependency(MessengerDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(MessengerDependencyProvider::SERVICE_ZED);
    }
}
