<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Communication;

use Spryker\Shared\Transfer\TransferInterface;

class Request extends AbstractObject implements EmbeddedTransferInterface
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
     * @return \Spryker\Shared\Transfer\TransferInterface|null
     */
    public function getMetaTransfer($name)
    {
        if (isset($this->values['metaTransfers'][$name])) {
            $className = $this->values['metaTransfers'][$name]['className'];
            $transfer = $this->createTransferObject($className);
            $transfer->fromArray($this->values['metaTransfers'][$name]['data']);

            return $transfer;
        }

        return null;
    }

    /**
     * @param string $name
     * @param \Spryker\Shared\Transfer\TransferInterface $transferObject
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
     * @deprecated Not used anymore.
     *
     * @return \Spryker\Shared\Transfer\TransferInterface|null
     */
    public function getTransfer()
    {
        return null;
    }

    /**
     * @param \Spryker\Shared\Transfer\TransferInterface $transferObject
     *
     * @return $this
     */
    public function setTransfer(TransferInterface $transferObject)
    {
        $this->values['transfer'] = $transferObject->toArray(false);
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
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    protected function createTransferObject($transferClassName)
    {
        $transfer = new $transferClassName();

        return $transfer;
    }

}
