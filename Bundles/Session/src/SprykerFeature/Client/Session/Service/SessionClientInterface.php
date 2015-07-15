<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Session\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface SessionClientInterface extends SessionInterface
{

    /**
     * @param SessionInterface $container
     *
     * @return SessionClientInterface
     */
    public function setContainer(SessionInterface $container);

}
