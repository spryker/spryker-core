<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Transfer\Fixtures;

use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerEngine\Shared\Transfer\AbstractTransfer as ParentAbstractTransfer;

class AbstractTransfer extends ParentAbstractTransfer
{

    /**
     * @var string
     */
    protected $string;

    /**
     * @var int
     */
    protected $integer;

    /**
     * @var bool
     */
    protected $bool;

    /**
     * @var array
     */
    protected $array;

    /**
     * @var TransferInterface
     */
    protected $transfer;

    /**
     * @var \ArrayObject|TransferInterface[]
     */
    protected $transferCollection;

    public function __construct()
    {
        $this->transferCollection = new \ArrayObject();
    }

    /**
     * @return string
     */
    protected function getNamespace()
    {
        return 'Unit\\SprykerEngine\\Shared\\Transfer\\Fixtures\\';
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @param string $string
     *
     * @return AbstractTransfer
     */
    public function setString($string)
    {
        $this->string = $string;
        $this->addModifiedProperty('string');

        return $this;
    }

    /**
     * @return int
     */
    public function getInteger()
    {
        return $this->integer;
    }

    /**
     * @param int $integer
     *
     * @return AbstractTransfer
     */
    public function setInteger($integer)
    {
        $this->integer = $integer;
        $this->addModifiedProperty('integer');

        return $this;
    }

    /**
     * @return bool
     */
    public function getBool()
    {
        return $this->bool;
    }

    /**
     * @param bool $bool
     *
     * @return AbstractTransfer
     */
    public function setBool($bool)
    {
        $this->bool = $bool;
        $this->addModifiedProperty('bool');

        return $this;
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return $this->array;
    }

    /**
     * @param array|\ArrayObject $array
     *
     * @return AbstractTransfer
     */
    public function setArray($array)
    {
        $this->array = $array;
        $this->addModifiedProperty('array');

        return $this;
    }

    /**
     * @return TransferInterface
     */
    public function getTransfer()
    {
        return $this->transfer;
    }

    /**
     * @param TransferInterface $transfer
     *
     * @return AbstractTransfer
     */
    public function setTransfer(TransferInterface $transfer)
    {
        $this->transfer = $transfer;
        $this->addModifiedProperty('transfer');

        return $this;
    }

    /**
     * @return AbstractTransfer[]|\ArrayObject()
     */
    public function getTransferCollection()
    {
        return $this->transferCollection;
    }

    /**
     * @param \ArrayObject|AbstractTransfer[] $transferCollection
     *
     * @return AbstractTransfer
     */
    public function setTransferCollection(\ArrayObject $transferCollection)
    {
        $this->transferCollection = $transferCollection;
        $this->addModifiedProperty('transferCollection');

        return $this;
    }

}
