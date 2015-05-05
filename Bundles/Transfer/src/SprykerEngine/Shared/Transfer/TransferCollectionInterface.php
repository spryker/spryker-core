<?php

namespace SprykerEngine\Shared\Transfer;

interface TransferCollectionInterface extends
    TransferInterface,
    \IteratorAggregate,
    \ArrayAccess,
    \Countable
{
    /**
     * @param AbstractTransfer $transfer
     */
    public function add(AbstractTransfer $transfer);

    /**
     * @param AbstractTransfer $transfer
     * @return bool
     */
    public function has(AbstractTransfer $transfer);

    /**
     * @param AbstractTransfer $transfer
     */
    public function remove(AbstractTransfer $transfer);

    /**
     * @return AbstractTransfer
     */
    public function getFirstItem();

    /**
     * @return AbstractTransfer
     */
    public function getEmptyTransferItem();

    /**
     * @return string
     */
    public function getTransferObjectClass();

    /**
     * @param int $offset
     * @param int $length
     */
    public function slice($offset = null, $length = null);
}
