<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntityRuleDataImportStep;

use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntityRuleDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AclEntityRulePermissionMaskToBinaryStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected const INVALID_PERMISSION_TEMPLATE = 'Invalid permission given: %s. Use one of: %s';

    /**
     * @var string
     */
    protected const OPERATION_CREATE = 'C';

    /**
     * @var string
     */
    protected const OPERATION_READ = 'R';

    /**
     * @var string
     */
    protected const OPERATION_UPDATE = 'U';

    /**
     * @var string
     */
    protected const OPERATION_DELETE = 'D';

    /**
     * @var array<int>
     */
    protected $permissionMaskBinaryMap = [
        self::OPERATION_CREATE => AclEntityConstants::OPERATION_MASK_CREATE,
        self::OPERATION_READ => AclEntityConstants::OPERATION_MASK_READ,
        self::OPERATION_UPDATE => AclEntityConstants::OPERATION_MASK_UPDATE,
        self::OPERATION_DELETE => AclEntityConstants::OPERATION_MASK_DELETE,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $binaryPermissionMask = 0;
        foreach (str_split($dataSet[AclEntityRuleDataSetInterface::PERMISSION_MASK]) as $permission) {
            if (!isset($this->permissionMaskBinaryMap[strtoupper($permission)])) {
                throw new DataImportException(
                    sprintf(
                        static::INVALID_PERMISSION_TEMPLATE,
                        $permission,
                        implode(', ', array_keys($this->permissionMaskBinaryMap))
                    )
                );
            }
            $binaryPermissionMask |= $this->permissionMaskBinaryMap[strtoupper($permission)];
        }

        $dataSet[AclEntityRuleDataSetInterface::PERMISSION_MASK] = $binaryPermissionMask;
    }
}
