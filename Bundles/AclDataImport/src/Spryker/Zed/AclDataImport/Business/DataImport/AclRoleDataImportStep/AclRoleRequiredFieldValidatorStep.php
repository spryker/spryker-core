<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclDataImport\Business\DataImport\AclRoleDataImportStep;

use Spryker\Zed\AclDataImport\Business\DataImport\AbstractRequiredFieldsValidatorStep;
use Spryker\Zed\AclDataImport\Business\DataSet\AclRoleDataSetInterface;

class AclRoleRequiredFieldValidatorStep extends AbstractRequiredFieldsValidatorStep
{
    /**
     * @return array<string>
     */
    public function getRequiredFieldList(): array
    {
        return [AclRoleDataSetInterface::ACL_ROLE_NAME];
    }
}
