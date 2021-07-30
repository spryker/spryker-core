<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentDataImportStep;

use Spryker\Zed\AclEntityDataImport\Business\DataImport\AbstractRequiredFieldsValidatorStep;
use Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntitySegmentDataSetInterface;

class AclEntitySegmentRequiredFieldValidatorStep extends AbstractRequiredFieldsValidatorStep
{
    /**
     * @return string[]
     */
    public function getRequiredFieldList(): array
    {
        return [
            AclEntitySegmentDataSetInterface::NAME,
            AclEntitySegmentDataSetInterface::REFERENCE,
        ];
    }
}
