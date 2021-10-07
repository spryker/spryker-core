<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntityRuleDataImportStep;

use Spryker\Zed\AclEntityDataImport\Business\DataImport\AbstractRequiredFieldsValidatorStep;
use Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntityRuleDataSetInterface;

class AclEntityRuleRequiredFieldValidatorStep extends AbstractRequiredFieldsValidatorStep
{
    /**
     * @return array<string>
     */
    public function getRequiredFieldList(): array
    {
        return [
            AclEntityRuleDataSetInterface::ACL_ROLE_REFERENCE,
            AclEntityRuleDataSetInterface::ENTITY,
            AclEntityRuleDataSetInterface::SCOPE,
            AclEntityRuleDataSetInterface::PERMISSION_MASK,
        ];
    }
}
