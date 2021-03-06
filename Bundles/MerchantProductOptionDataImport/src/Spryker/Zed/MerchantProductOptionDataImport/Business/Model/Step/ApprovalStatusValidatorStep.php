<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOptionDataImport\Business\Model\Step;

use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOptionDataImport\Business\Model\DataSet\MerchantProductOptionDataSetInterface;

class ApprovalStatusValidatorStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Zed\MerchantProductOption\MerchantProductOptionConfig::STATUS_WAITING_FOR_APPROVAL
     */
    protected const STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @uses \Spryker\Zed\MerchantProductOption\MerchantProductOptionConfig::STATUS_APPROVED
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Zed\MerchantProductOption\MerchantProductOptionConfig::STATUS_DENIED
     */
    protected const STATUS_DENIED = 'denied';

    protected const DEFAULT_APPROVAL_STATUS = 'waiting_for_approval';

    /**
     * @phpstan-param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<mixed> $dataSet
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $approvalStatuses = [
            static::STATUS_APPROVED,
            static::STATUS_WAITING_FOR_APPROVAL,
            static::STATUS_DENIED,
        ];

        $currentApprovalStatus = $dataSet[MerchantProductOptionDataSetInterface::APPROVAL_STATUS];

        if (!$currentApprovalStatus) {
            $dataSet[MerchantProductOptionDataSetInterface::APPROVAL_STATUS] = static::DEFAULT_APPROVAL_STATUS;
            $currentApprovalStatus = static::DEFAULT_APPROVAL_STATUS;
        }

        if (!in_array($currentApprovalStatus, $approvalStatuses)) {
            throw new InvalidDataException('"' . MerchantProductOptionDataSetInterface::APPROVAL_STATUS . '" has wrong data');
        }
    }
}
