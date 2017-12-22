<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Messenger\Dependency\Client;

interface MessengerToSessionClientInterface
{
    /**
     * @param string $name
     *
     * @return \Symfony\Component\HttpFoundation\Session\SessionBagInterface
     */
    public function getBag($name);
}
