<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\ZedRequest\Client;

use Spryker\Shared\Transfer\TransferInterface;

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
     * @return self
     */
    public function setHost($host)
    {
        $this->values['host'] = $host;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return \Spryker\Shared\Transfer\TransferInterface|null
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

        return null;
    }

    /**
     * @param string $name
     * @param \Spryker\Shared\Transfer\TransferInterface $transferObject
     *
     * @return self
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
     * @return self
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
     * @return self
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
     * @return self
     */
    public function setTime($time)
    {
        $this->values['time'] = $time;

        return $this;
    }

    /**
     * @return \Spryker\Shared\Transfer\TransferInterface|null
     */
    public function getTransfer()
    {
        if (!empty($this->values['transferClassName'])) {
            $transfer = $this->createTransferObject(
                $this->values['transferClassName']
            );

            if (!empty($this->values['transfer'])) {
                $transfer->fromArray($this->values['transfer'], true);
            }

            return $transfer;
        }

        return null;
    }

    /**
     * @param \Spryker\Shared\Transfer\TransferInterface $transferObject
     *
     * @return self
     */
    public function setTransfer(TransferInterface $transferObject)
    {
        $this->values['transfer'] = $transferObject->modifiedToArray(true);
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
     * @return self
     */
    public function setUsername($username)
    {
        $this->values['username'] = $username;

        return $this;
    }

    /**
     * @param $transferClassName
     *
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    private function createTransferObject($transferClassName)
    {
        $transfer = new $transferClassName();

        return $transfer;
    }

}
