<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business;

use Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReader;
use Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness\RequiredFieldValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Type\BooleanFieldTypeValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Type\DecimalFieldTypeValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Type\IntegerFieldTypeValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Type\StringFieldTypeValidator;
use Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriter;
use Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface getRepository()
 * @method \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface getEntityManager()
 */
class DynamicEntityBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface
     */
    public function createDynamicEntityReader(): DynamicEntityReaderInterface
    {
        return new DynamicEntityReader(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriterInterface
     */
    public function createDynamicEntityWriter(): DynamicEntityWriterInterface
    {
        return new DynamicEntityWriter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createDynamicEntityValidator(),
            $this->createDynamicEntityUpdateValidator(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createDynamicEntityValidator(): DynamicEntityValidatorInterface
    {
        return new DynamicEntityValidator(
            $this->getDynamicEntityValidators(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createDynamicEntityUpdateValidator(): DynamicEntityValidatorInterface
    {
        return new DynamicEntityValidator(
            $this->getDynamicEntityUpdateValidators(),
        );
    }

    /**
     * @return array<\Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface>
     */
    public function getDynamicEntityValidators(): array
    {
        return [
            $this->createRequiredFieldValidator(),
            $this->createIntegerFieldTypeValidator(),
            $this->createStringFieldTypeValidator(),
            $this->createBooleanFieldTypeValidator(),
            $this->createDecimalFeildTypeValidator(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface>
     */
    public function getDynamicEntityUpdateValidators(): array
    {
        return [
            $this->createIntegerFieldTypeValidator(),
            $this->createStringFieldTypeValidator(),
            $this->createBooleanFieldTypeValidator(),
            $this->createDecimalFeildTypeValidator(),
        ];
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createRequiredFieldValidator(): DynamicEntityValidatorInterface
    {
        return new RequiredFieldValidator();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createIntegerFieldTypeValidator(): DynamicEntityValidatorInterface
    {
        return new IntegerFieldTypeValidator();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createStringFieldTypeValidator(): DynamicEntityValidatorInterface
    {
        return new StringFieldTypeValidator();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createBooleanFieldTypeValidator(): DynamicEntityValidatorInterface
    {
        return new BooleanFieldTypeValidator();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createDecimalFeildTypeValidator(): DynamicEntityValidatorInterface
    {
        return new DecimalFieldTypeValidator();
    }
}
