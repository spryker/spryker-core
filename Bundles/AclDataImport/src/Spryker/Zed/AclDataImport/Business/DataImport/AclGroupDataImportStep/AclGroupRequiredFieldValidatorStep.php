<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclDataImport\Business\DataImport\AclGroupDataImportStep;

use Spryker\Zed\AclDataImport\Business\DataImport\AbstractRequiredFieldsValidatorStep;
use Spryker\Zed\AclDataImport\Business\DataSet\AclGroupDataImportInterface;

class AclGroupRequiredFieldValidatorStep extends AbstractRequiredFieldsValidatorStep
{
    /**
     * @return array<string>
     */
    public function getRequiredFieldList(): array
    {
        return [AclGroupDataImportInterface::ACL_GROUP_NAME];
    }
}
