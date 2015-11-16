<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Transfer\Fixtures;

use SprykerEngine\Shared\Transfer\Exception\RequiredTransferPropertyException;
use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerEngine\Shared\Transfer\AbstractTransfer as ParentAbstractTransfer;

class AbstractTransfer extends ParentAbstractTransfer
{

    const STRING = 'string';

    const INTEGER = 'integer';

    const BOOL = 'bool';

    const ARR = 'arr';

    const TRANSFER = 'transfer';

    const TRANSFER_COLLECTION = 'transferCollection';

    /**
     * @var string
     */
    protected $string;

    /**
     * @var integer
     */
    protected $integer;

    /**
     * @var bool
     */
    protected $bool;

    /**
     * @var array
     */
    protected $arr;

    /**
     * @var TransferInterface
     */
    protected $transfer;

    /**
     * @var \ArrayObject|TransferInterface[]
     */
    protected $transferCollection;

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::STRING => [
            'type' => 'string',
            'name_underscore' => 'string',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::INTEGER => [
            'type' => 'integer',
            'name_underscore' => 'integer',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::BOOL => [
            'type' => 'bool',
            'name_underscore' => 'bool',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::ARR => [
            'type' => 'array',
            'name_underscore' => 'arr',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::TRANSFER => [
            'type' => 'Unit\SprykerEngine\Shared\Transfer\Fixtures\AbstractTransfer',
            'name_underscore' => 'transfer',
            'is_collection' => false,
            'is_transfer' => true,
        ],
        self::TRANSFER_COLLECTION => [
            'type' => 'Unit\SprykerEngine\Shared\Transfer\Fixtures\AbstractTransfer',
            'name_underscore' => 'transfer_collection',
            'is_collection' => true,
            'is_transfer' => true,
        ],
    ];

    public function __construct()
    {
        $this->arr = new \ArrayObject();
        $this->transferCollection = new \ArrayObject();
    }

    /**
     * @param string $string
     *
     * @return $this
     */
    public function setString($string)
    {
        $this->string = $string;
        $this->addModifiedProperty(self::STRING);

        return $this;
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @throws RequiredTransferPropertyException
     *
     * @return self
     */
    public function requireString()
    {
        $this->assertPropertyIsSet(self::STRING);

        return $this;
    }

    /**
     * @param integer $integer
     *
     * @return $this
     */
    public function setInteger($integer)
    {
        $this->integer = $integer;
        $this->addModifiedProperty(self::INTEGER);

        return $this;
    }

    /**
     * @return integer
     */
    public function getInteger()
    {
        return $this->integer;
    }

    /**
     * @throws RequiredTransferPropertyException
     *
     * @return self
     */
    public function requireInteger()
    {
        $this->assertPropertyIsSet(self::INTEGER);

        return $this;
    }

    /**
     * @param bool $bool
     *
     * @return $this
     */
    public function setBool($bool)
    {
        $this->bool = $bool;
        $this->addModifiedProperty(self::BOOL);

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
     * @throws RequiredTransferPropertyException
     *
     * @return self
     */
    public function requireBool()
    {
        $this->assertPropertyIsSet(self::BOOL);

        return $this;
    }

    /**
     * @param array $arr
     *
     * @return $this
     */
    public function setArr(array $arr)
    {
        $this->arr = $arr;
        $this->addModifiedProperty(self::ARR);

        return $this;
    }

    /**
     * @return array
     */
    public function getArr()
    {
        return $this->arr;
    }

    /**
     * @param array $arr
     *
     * @return $this
     */
    public function addArr($arr)
    {
        $this->arr[] = $arr;
        $this->addModifiedProperty(self::ARR);

        return $this;
    }

    /**
     * @throws RequiredTransferPropertyException
     *
     * @return self
     */
    public function requireArr()
    {
        $this->assertCollectionPropertyIsSet(self::ARR);

        return $this;
    }

    /**
     * @param TransferInterface $transfer
     *
     * @return $this
     */
    public function setTransfer(TransferInterface $transfer)
    {
        $this->transfer = $transfer;
        $this->addModifiedProperty(self::TRANSFER);

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
     * @throws RequiredTransferPropertyException
     *
     * @return self
     */
    public function requireTransfer()
    {
        $this->assertPropertyIsSet(self::TRANSFER);

        return $this;
    }

    /**
     * @param \ArrayObject|TransferInterface[] $transferCollection
     *
     * @return $this
     */
    public function setTransferCollection(\ArrayObject $transferCollection)
    {
        $this->transferCollection = $transferCollection;
        $this->addModifiedProperty(self::TRANSFER_COLLECTION);

        return $this;
    }

    /**
     * @return TransferInterface[]
     */
    public function getTransferCollection()
    {
        return $this->transferCollection;
    }

    /**
     * @param TransferInterface $transferCollection
     *
     * @return $this
     */
    public function addTransferCollection(TransferInterface $transferCollection)
    {
        $this->transferCollection[] = $transferCollection;
        $this->addModifiedProperty(self::TRANSFER_COLLECTION);

        return $this;
    }

    /**
     * @throws RequiredTransferPropertyException
     *
     * @return self
     */
    public function requireTransferCollection()
    {
        $this->assertCollectionPropertyIsSet(self::TRANSFER_COLLECTION);

        return $this;
    }

}
