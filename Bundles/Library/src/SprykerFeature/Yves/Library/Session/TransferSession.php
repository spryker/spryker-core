<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Library\Session;

use SprykerEngine\Shared\Transfer\TransferInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TransferSession
{

    /**
     * @var Session
     */
    protected $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param string $name
     * @param TransferInterface $transferObject
     */
    public function set($name, TransferInterface $transferObject)
    {
        $this->session->set($name, $transferObject->toArray(false));
    }

    /**
     * @param string $name
     * @param TransferInterface $transferObject
     *
     * @return TransferInterface
     */
    public function get($name, TransferInterface $transferObject)
    {
        $transferArray = $this->session->get($name);
        if (!empty($transferArray)) {
            $transferObject->fromArray($transferArray, true);
        }

        return $transferObject;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return $this->session->has($name);
    }

    /**
     * @param string $name
     *
     * @return mixed The removed value or null when it does not exist
     */
    public function remove($name)
    {
        return $this->session->remove($name);
    }

}
