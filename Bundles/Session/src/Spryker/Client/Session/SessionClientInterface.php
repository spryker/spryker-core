<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Session;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface SessionClientInterface extends SessionInterface
{

    /**
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $container
     *
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    public function setContainer(SessionInterface $container);

}
