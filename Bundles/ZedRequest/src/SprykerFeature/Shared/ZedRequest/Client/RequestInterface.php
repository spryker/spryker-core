<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\ZedRequest\Client;

use SprykerEngine\Shared\Transfer\TransferInterface;

interface RequestInterface
{
    /**
     * @return string
     */
    public function getHost();

    /**
     * @param string $host
     * @return $this
     */
    public function setHost($host);

    /**
     * @param string $name
     * @return TransferInterface
     */
    public function getMetaTransfer($name);

    /**
     * @param string $name
     * @param TransferInterface $transferObject
     * @return $this
     */
    public function addMetaTransfer($name, TransferInterface $transferObject);

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password);

    /**
     * @return string
     */
    public function getSessionId();

    /**
     * @param string $sessionId
     * @return $this
     */
    public function setSessionId($sessionId);

    /**
     * @return string
     */
    public function getTime();

    /**
     * @param string $time
     * @return $this
     */
    public function setTime($time);

    /**
     * @return TransferInterface
     */
    public function getTransfer();

    /**
     * @param TransferInterface $transferObject
     * @return $this
     */
    public function setTransfer(TransferInterface $transferObject);

    /**
     * @return string
     */
    public function getUsername();

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username);
}
