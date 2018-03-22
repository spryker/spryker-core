<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Messenger;

use Spryker\Client\Kernel\AbstractFactory;

class MessengerFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Messenger\Dependency\Client\MessengerToSessionClientInterface
     */
    public function getSessionClient()
    {
        return $this->getProvidedDependency(MessengerDependencyProvider::CLIENT_SESSION);
    }
}
