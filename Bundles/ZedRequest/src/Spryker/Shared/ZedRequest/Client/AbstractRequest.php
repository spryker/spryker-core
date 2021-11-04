<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Client;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

abstract class AbstractRequest extends AbstractObject implements EmbeddedTransferInterface, RequestInterface
{
    /**
     * @var array<string, mixed>
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
     * @param array|null $values
     */
    public function __construct(?array $values = null)
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
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|null
     */
    public function getMetaTransfer($name)
    {
        if (isset($this->values['metaTransfers'][$name])) {
            $transfer = $this->createTransferObject(
                $this->values['metaTransfers'][$name]['className'],
            );
            $transfer->fromArray($this->values['metaTransfers'][$name]['data']);

            return $transfer;
        }

        return null;
    }

    /**
     * @param string $name
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transferObject
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
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|null
     */
    public function getTransfer()
    {
        if (!empty($this->values['transferClassName'])) {
            $transfer = $this->createTransferObject(
                $this->values['transferClassName'],
            );

            if (!empty($this->values['transfer'])) {
                $transfer->fromArray($this->values['transfer'], true);
            }

            return $transfer;
        }

        return null;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transferObject
     *
     * @return $this
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
     * @return $this
     */
    public function setUsername($username)
    {
        $this->values['username'] = $username;

        return $this;
    }

    /**
     * @param string $transferClassName
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    private function createTransferObject($transferClassName)
    {
        $transfer = new $transferClassName();

        return $transfer;
    }
}
