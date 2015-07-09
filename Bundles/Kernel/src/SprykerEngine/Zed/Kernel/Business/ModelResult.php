<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Business;

use SprykerEngine\Shared\Transfer\TransferInterface;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

class ModelResult
{

    /**
     * @var ActiveRecordInterface
     */
    protected $entity = null;

    /**
     * @var array
     */
    protected $entityModifiedColumns = [];

    /**
     * @var TransferInterface
     */
    protected $transfer = null;

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @deprecated
     *
     * @param ActiveRecordInterface $entity
     */
    public function __construct(ActiveRecordInterface $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * @deprecated
     *
     * @return bool
     */
    public function isSuccess()
    {
        return count($this->getErrors()) < 1;
    }

    /**
     * @deprecated
     *
     * @return array
     */
    public function getErrors()
    {
        return array_merge($this->errors, $this->getPropelValidationErrors());
    }

    /**
     * @return array
     */
    protected function getPropelValidationErrors()
    {
        return [];
    }

    /**
     * @deprecated
     *
     * @return TransferInterface
     */
    public function getTransfer()
    {
        return $this->transfer;
    }

    /**
     * @deprecated
     *
     * @param TransferInterface $transfer
     *
     * @return $this
     */
    public function setTransfer(TransferInterface $transfer)
    {
        $this->transfer = $transfer;

        return $this;
    }

    /**
     * @deprecated
     *
     * @return bool
     */
    public function hasTransfer()
    {
        return isset($this->transfer);
    }

    /**
     * @deprecated
     *
     * @return bool
     */
    public function hasEntity()
    {
        return isset($this->entity);
    }

    /**
     * @deprecated
     *
     * @return ActiveRecordInterface
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @deprecated
     *
     * @param ActiveRecordInterface $entity
     *
     * @return $this
     */
    public function setEntity(ActiveRecordInterface $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @deprecated
     *
     * @param array $entityModifiedColumns
     */
    public function setEntityModifiedColumns($entityModifiedColumns)
    {
        $this->entityModifiedColumns = $entityModifiedColumns;
    }

    /**
     * @deprecated
     *
     * @return array
     */
    public function getEntityModifiedColumns()
    {
        return $this->entityModifiedColumns;
    }

    /**
     * @deprecated
     *
     * @param string $error
     *
     * @return $this
     */
    public function addError($error)
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @deprecated
     *
     * @param array $errors
     */
    public function addErrors(array $errors)
    {
        foreach ($errors as $error) {
            $this->addError($error);
        }
    }

}
