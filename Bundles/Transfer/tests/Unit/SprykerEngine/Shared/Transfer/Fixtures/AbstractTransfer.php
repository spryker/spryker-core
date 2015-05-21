<?php

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
     * @var TransferInterface
     */
    protected $transferCollection;

    public function __construct()
    {
        $this->transferCollection = new \ArrayObject();
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
     * @return boolean
     */
    public function getBool()
    {
        return $this->bool;
    }

    /**
     * @param boolean $bool
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
     * @return TransferInterface
     */
    public function getTransferCollection()
    {
        return $this->transferCollection;
    }

    /**
     * @param TransferInterface $transferCollection
     *
     * @return AbstractTransfer
     */
    public function setTransferCollection(TransferInterface $transferCollection)
    {
        $this->transferCollection = $transferCollection;
        $this->addModifiedProperty('transferCollection');

        return $this;
    }


}
