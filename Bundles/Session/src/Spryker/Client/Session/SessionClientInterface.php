<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Session;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface SessionClientInterface extends SessionInterface
{

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $container
     *
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    public function setContainer(SessionInterface $container);

}
