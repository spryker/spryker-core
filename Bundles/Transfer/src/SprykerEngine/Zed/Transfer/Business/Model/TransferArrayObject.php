<?php


namespace SprykerEngine\Zed\Transfer\Business\Model;


use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;
use SprykerFeature\Shared\Library\TransferObject\TransferCollectionInterface;

/**
 * Class TransferArrayObject
 *
 * @deprecated
 * @package SprykerEngine\Zed\Transfer\Business\Model
 */
class TransferArrayObject extends \ArrayObject implements TransferCollectionInterface
{
    /**
     * @param AbstractTransfer $transfer
     */
    public function add(AbstractTransfer $transfer)
    {
        $this->storage->append($transfer);
    }

    /**
     * @param AbstractTransfer $transfer
     * @return bool
     */
    public function has(AbstractTransfer $transfer)
    {
        // TODO: Implement has() method.
    }

    /**
     * @param AbstractTransfer $transfer
     */
    public function remove(AbstractTransfer $transfer)
    {
        // TODO: Implement remove() method.
    }

    /**
     * @return AbstractTransfer
     */
    public function getFirstItem()
    {
        // TODO: Implement getFirstItem() method.
    }

    /**
     * @return AbstractTransfer
     */
    public function getEmptyTransferItem()
    {
        // TODO: Implement getEmptyTransferItem() method.
    }

    /**
     * @return string
     */
    public function getTransferObjectClass()
    {
        // TODO: Implement getTransferObjectClass() method.
    }

    /**
     * @param int $offset
     * @param int $length
     */
    public function slice($offset = null, $length = null)
    {
        // TODO: Implement slice() method.
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        // TODO: Implement isEmpty() method.
    }

    /**
     * @param bool $includeNullValues
     * @param bool $recursive
     * @param bool $formatToUnderscore
     * @return array
     */
    public function toArray($includeNullValues = true, $recursive = true, $formatToUnderscore = true)
    {
        // TODO: Implement toArray() method.
    }

    /**
     * @param bool $recursive
     * @param bool $formatToUnderscore
     * @return array
     */
    public function modifiedToArray($recursive = true, $formatToUnderscore = true)
    {
        // TODO: Implement modifiedToArray() method.
    }

    /**
     * @param array $values
     * @param bool $fuzzyMatch
     */
    public function fromArray(array $values, $fuzzyMatch = false)
    {
        // TODO: Implement fromArray() method.
    }

    /**
     * @throws \RuntimeException
     */
    public function validate()
    {
        // TODO: Implement validate() method.
    }
}