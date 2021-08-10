<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentConnectorDataImportStep;

use Spryker\Zed\AclEntityDataImport\Business\DataImport\AbstractRequiredFieldsValidatorStep;
use Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntitySegmentConnectorDataSetInterface;

class AclEntitySegmentConnectorRequiredFieldValidatorStep extends AbstractRequiredFieldsValidatorStep
{
    /**
     * @return string[]
     */
    public function getRequiredFieldList(): array
    {
        return [
            AclEntitySegmentConnectorDataSetInterface::DATA_ENTITY,
            AclEntitySegmentConnectorDataSetInterface::REFERENCE_FIELD,
            AclEntitySegmentConnectorDataSetInterface::ENTITY_REFERENCE,
            AclEntitySegmentConnectorDataSetInterface::ACL_ENTITY_SEGMENT_REFERENCE,
        ];
    }
}
