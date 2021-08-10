<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclDataImport\Business\DataImport;

use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

abstract class AbstractRequiredFieldsValidatorStep implements DataImportStepInterface
{
    /**
     * @return string[]
     */
    abstract public function getRequiredFieldList(): array;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        foreach ($this->getRequiredFieldList() as $requiredField) {
            if (empty($dataSet[$requiredField])) {
                throw new DataImportException(sprintf('Field "%s" cannot be empty', $requiredField));
            }
        }
    }
}
