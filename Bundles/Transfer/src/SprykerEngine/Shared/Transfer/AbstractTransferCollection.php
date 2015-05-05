<?php

namespace SprykerEngine\Shared\Transfer;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

abstract class AbstractTransferCollection implements
    \IteratorAggregate,
    \ArrayAccess,
    \Countable,
    TransferCollectionInterface
{

    /**
     * @var string
     */
    protected $transferObjectClass;

    /**
     * @var array
     */
    protected $transferObjects = [];

    /**
     * @var int
     */
    protected $dirtyObjects = 0;


    /**
     * @todo think of type checks
     * @param AbstractTransfer $transfer
     */
    public function add(AbstractTransfer $transfer)
    {
//        assert($transfer instanceof $this->transferObjectClass);
        if (method_exists($transfer, 'getId') && $transfer->getId() > 0) {
            $this->transferObjects[$transfer->getId()] = $transfer;
        } else {
            $this->transferObjects[-- $this->dirtyObjects] = $transfer;
        }
    }

    /**
     * @todo think of type checks
     * @param AbstractTransfer $transfer
     * @return bool
     */
    public function has(AbstractTransfer $transfer)
    {
//        assert($transfer instanceof $this->transferObjectClass);
        $id = $this->findIdForTransfer($transfer);
        if ($id !== false && array_key_exists($id, $this->transferObjects)) {
            return true;
        }
        return false;
    }

    /**
     * @todo think of type checks
     * @param AbstractTransfer $transfer
     */
    public function remove(AbstractTransfer $transfer)
    {
//        assert($transfer instanceof $this->transferObjectClass);
        $id = $this->findIdForTransfer($transfer);
        if ($id !== false && array_key_exists($id, $this->transferObjects)) {
            unset($this->transferObjects[$id]);
        }
    }

    /**
     * @param AbstractTransfer $transfer
     * @return mixed|null|false
     */
    protected function findIdForTransfer(AbstractTransfer $transfer)
    {
        if (method_exists($transfer, 'getId') && $transfer->getId() > 0) {
            $id = $transfer->getId();
        } else {
            $id = array_search($transfer, $this->transferObjects, true);
        }

        return $id;
    }

    /**
     * @return AbstractTransfer
     */
    public function getFirstItem()
    {
        return reset($this->transferObjects);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->transferObjects);
    }

    /**
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->transferObjects);
    }

    /**
     * @param $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->transferObjects[$offset];
    }

    /**
     * @param $offset
     * @param $value
     */
    public function offsetSet($offset, $value)
    {
        assert($value instanceof $this->transferObjectClass);
        $this->transferObjects[$offset] = $value;
    }

    /**
     * @param $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->transferObjects[$offset]);
    }

    /**
     * @param bool $includeNullValues
     * @param bool $recursive
     * @param bool $formatToUnderscore
     * @return array
     */
    public function toArray($includeNullValues = true, $recursive = true, $formatToUnderscore = true)
    {
        $data = [];

        foreach ($this->transferObjects as $key => $object) {
            if ($recursive && $object instanceof TransferInterface) {
                $data[$key] = $object->toArray($includeNullValues, $formatToUnderscore);
            } else {
                $data[$key] = $object;
            }
        }

        return $data;
    }

    /**
     * @param bool $recursive
     * @param bool $formatToUnderscore
     * @return array
     */
    public function modifiedToArray($recursive = true, $formatToUnderscore = true)
    {
        $data = [];
        foreach ($this->transferObjects as $key => $object) {
            if ($object instanceof TransferInterface) {
                $data[$key] = $object->modifiedToArray();
            } else {
                $data[$key] = $object;
            }
        }

        return $data;
    }

    /**
     * @param array $transferItems
     * @param bool $fuzzyMatch
     * @throws \InvalidArgumentException
     */
    public function fromArray(array $transferItems, $fuzzyMatch = false)
    {
        foreach ($transferItems as $transferItem) {
            if ($transferItem instanceof AbstractTransfer) {
                $this->add($transferItem);
            } elseif (is_array($transferItem)) {
                $transferObject = $this->createTransferObject();
                $transferObject->fromArray($transferItem, $fuzzyMatch);
                $this->add($transferObject);
            } else {
                throw new \InvalidArgumentException('fromArray() expects matching transfer object or array');
            }
        }
    }

    /**
     * @return AbstractTransfer
     */
    public function getEmptyTransferItem()
    {
        return new $this->transferObjectClass($this->locator);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->transferObjects);
    }

    /**
     * @return string
     */
    public function getTransferObjectClass()
    {
        return $this->transferObjectClass;
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->transferObjects);
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['transferObjects', 'transferObjectClass', 'dirtyObjects'];
    }

    public function __clone()
    {
        foreach ($this->transferObjects as $key => $value) {
            $this->transferObjects[$key] = clone $value;
        }
    }

    /**
     * @param int $offset
     * @param int $length
     */
    public function slice($offset = null, $length = null)
    {
        $this->transferObjects = array_slice($this->transferObjects, $offset, $length, true);
    }

    /**
     * @throws \Exception
     */
    public function validate()
    {
        foreach ($this->transferObjects as $object) {
            if ($object instanceof TransferInterface) {
                $object->validate();
            }
        }
    }

    /**
     * @return TransferInterface|AbstractTransfer
     */
    private function createTransferObject()
    {
        $transferNameParts = explode('\\', $this->transferObjectClass);
        $bundle = array_shift($transferNameParts);
        $locatorMethodName = 'transfer' . implode($transferNameParts);
        /** @var TransferInterface $transferObject */
        $transferObject = $this->locator->$bundle()->$locatorMethodName();
        return $transferObject;
    }
}
