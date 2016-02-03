<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\ZedRequest\Client;

use Spryker\Shared\Transfer\TransferInterface;

interface RequestInterface
{

    /**
     * @return string
     */
    public function getHost();

    /**
     * @param string $host
     *
     * @return self
     */
    public function setHost($host);

    /**
     * @param string $name
     *
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    public function getMetaTransfer($name);

    /**
     * @param string $name
     * @param \Spryker\Shared\Transfer\TransferInterface $transferObject
     *
     * @return self
     */
    public function addMetaTransfer($name, TransferInterface $transferObject);

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @param string $password
     *
     * @return self
     */
    public function setPassword($password);

    /**
     * @return string
     */
    public function getSessionId();

    /**
     * @param string $sessionId
     *
     * @return self
     */
    public function setSessionId($sessionId);

    /**
     * @return string
     */
    public function getTime();

    /**
     * @param string $time
     *
     * @return self
     */
    public function setTime($time);

    /**
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    public function getTransfer();

    /**
     * @param \Spryker\Shared\Transfer\TransferInterface $transferObject
     *
     * @return self
     */
    public function setTransfer(TransferInterface $transferObject);

    /**
     * @return string
     */
    public function getUsername();

    /**
     * @param string $username
     *
     * @return self
     */
    public function setUsername($username);

}
