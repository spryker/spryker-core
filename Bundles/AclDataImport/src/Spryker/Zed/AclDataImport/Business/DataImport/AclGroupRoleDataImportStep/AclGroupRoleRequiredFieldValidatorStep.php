<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclDataImport\Business\DataImport\AclGroupRoleDataImportStep;

use Spryker\Zed\AclDataImport\Business\DataImport\AbstractRequiredFieldsValidatorStep;
use Spryker\Zed\AclDataImport\Business\DataSet\AclGroupRoleDataImportInterface;

class AclGroupRoleRequiredFieldValidatorStep extends AbstractRequiredFieldsValidatorStep
{
    /**
     * @return string[]
     */
    public function getRequiredFieldList(): array
    {
        return [
            AclGroupRoleDataImportInterface::ACL_GROUP_REFERENCE,
            AclGroupRoleDataImportInterface::ACL_ROLE_REFERENCE,
        ];
    }
}
