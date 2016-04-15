<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Business;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Shared\Transfer\TransferInterface;

/**
 * //TODO: Move this class away from Kernel bundle.
 */
class ModelResult
{

    /**
     * @var \Propel\Runtime\ActiveRecord\ActiveRecordInterface|null
     */
    protected $entity = null;

    /**
     * @var array
     */
    protected $entityModifiedColumns = [];

    /**
     * @var \Spryker\Shared\Transfer\TransferInterface|null
     */
    protected $transfer = null;

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var bool
     */
    protected $success = true;

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface|null $entity
     */
    public function __construct(ActiveRecordInterface $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
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
     * @return \Spryker\Shared\Transfer\TransferInterface|null
     */
    public function getTransfer()
    {
        return $this->transfer;
    }

    /**
     * @param \Spryker\Shared\Transfer\TransferInterface $transfer
     *
     * @return $this
     */
    public function setTransfer(TransferInterface $transfer)
    {
        $this->transfer = $transfer;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasTransfer()
    {
        return isset($this->transfer);
    }

    /**
     * @return bool
     */
    public function hasEntity()
    {
        return isset($this->entity);
    }

    /**
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @deprecated
     *
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return $this
     */
    public function setEntity(ActiveRecordInterface $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @param array $entityModifiedColumns
     *
     * @return void
     */
    public function setEntityModifiedColumns($entityModifiedColumns)
    {
        $this->entityModifiedColumns = $entityModifiedColumns;
    }

    /**
     * @return array
     */
    public function getEntityModifiedColumns()
    {
        return $this->entityModifiedColumns;
    }

    /**
     * @param string $error
     *
     * @return $this
     */
    public function addError($error)
    {
        $this->success = false;
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @param array $errors
     *
     * @return void
     */
    public function addErrors(array $errors)
    {
        foreach ($errors as $error) {
            $this->addError($error);
        }
    }

    /**
     * @param bool $success
     *
     * @return $this
     */
    public function setSuccess($success)
    {
        $this->success = $success;

        return $this;
    }

}
