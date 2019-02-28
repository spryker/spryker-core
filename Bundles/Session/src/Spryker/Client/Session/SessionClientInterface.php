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
     * Specification:
     * - Sets the container for the session.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $container
     *
     * @return void
     */
    public function setContainer(SessionInterface $container);
}
