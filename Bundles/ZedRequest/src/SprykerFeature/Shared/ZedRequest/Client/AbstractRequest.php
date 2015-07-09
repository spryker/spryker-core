<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\ZedRequest\Client;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;

abstract class AbstractRequest extends AbstractObject implements EmbeddedTransferInterface, RequestInterface
{

    /**
     * @var array
     */
    protected $values = [
        'host' => null,
        'metaTransfers' => [],
        'password' => null,
        'sessionId' => null,
        'time' => null,
        'transfer' => null,
        'transferClassName' => null,
        'username' => null,
    ];

    /**
     * @param array $values
     */
    public function __construct(array $values = null)
    {
        parent::__construct($values);
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->values['host'];
    }

    /**
     * @param string $host
     *
     * @return $this
     */
    public function setHost($host)
    {
        $this->values['host'] = $host;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return TransferInterface
     */
    public function getMetaTransfer($name)
    {
        if (isset($this->values['metaTransfers'][$name])) {
            $transfer = $this->createTransferObject(
                $this->values['metaTransfers'][$name]['className']
            );
            $transfer->fromArray($this->values['metaTransfers'][$name]['data']);

            return $transfer;
        }

        return;
    }

    /**
     * @param string $name
     * @param TransferInterface $transferObject
     *
     * @return $this
     */
    public function addMetaTransfer($name, TransferInterface $transferObject)
    {
        $this->values['metaTransfers'][$name] = [
            'data' => $transferObject->toArray(false),
            'className' => get_class($transferObject),
        ];

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->values['password'];
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->values['password'] = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->values['sessionId'];
    }

    /**
     * @param string $sessionId
     *
     * @return $this
     */
    public function setSessionId($sessionId)
    {
        $this->values['sessionId'] = $sessionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->values['time'];
    }

    /**
     * @param string $time
     *
     * @return $this
     */
    public function setTime($time)
    {
        $this->values['time'] = $time;

        return $this;
    }

    /**
     * @return TransferInterface
     */
    public function getTransfer()
    {
        if (!empty($this->values['transferClassName']) && !empty($this->values['transfer'])) {
            $transfer = $this->createTransferObject(
                $this->values['transferClassName']
            );
            $transfer->fromArray($this->values['transfer']);

            return $transfer;
        }

        return;
    }

    /**
     * @param TransferInterface $transferObject
     *
     * @return $this
     */
    public function setTransfer(TransferInterface $transferObject)
    {
        $this->values['transfer'] = $transferObject->toArray(true);
        $this->values['transferClassName'] = get_class($transferObject);

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->values['username'];
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->values['username'] = $username;

        return $this;
    }

    /**
     * @param $transferClassName
     *
     * @return TransferInterface
     */
    private function createTransferObject($transferClassName)
    {
        $transfer = new $transferClassName();

        return $transfer;
    }

}
