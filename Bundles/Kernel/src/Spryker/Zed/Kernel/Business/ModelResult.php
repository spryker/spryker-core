<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Business;

use Spryker\Shared\Transfer\TransferInterface;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

class ModelResult
{

    /**
     * @var \Propel\Runtime\ActiveRecord\ActiveRecordInterface
     */
    protected $entity = null;

    /**
     * @var array
     */
    protected $entityModifiedColumns = [];

    /**
     * @var \Spryker\Shared\Transfer\TransferInterface
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
     * @deprecated
     *
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
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
        return $this->success;
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
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    public function getTransfer()
    {
        return $this->transfer;
    }

    /**
     * @deprecated
     *
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
     * @deprecated
     *
     * @param array $entityModifiedColumns
     *
     * @return void
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
        $this->success = false;
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @deprecated
     *
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
