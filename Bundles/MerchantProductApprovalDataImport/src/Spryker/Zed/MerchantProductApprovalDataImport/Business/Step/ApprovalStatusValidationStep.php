<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductApprovalDataImport\Business\Step;

use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ApprovalStatusValidationStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected const APPROVAL_STATUS = 'approval_status';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const APPROVAL_STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_WAITING_FOR_APPROVAL
     *
     * @var string
     */
    protected const APPROVAL_STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_DENIED
     *
     * @var string
     */
    protected const APPROVAL_STATUS_DENIED = 'denied';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_DRAFT
     *
     * @var string
     */
    protected const APPROVAL_STATUS_DRAFT = 'draft';

    /**
     * @var array<string>
     */
    protected const ALLOWED_APPROVAL_STATUS_LIST = [
        self::APPROVAL_STATUS_APPROVED,
        self::APPROVAL_STATUS_WAITING_FOR_APPROVAL,
        self::APPROVAL_STATUS_DENIED,
        self::APPROVAL_STATUS_DRAFT,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $approvalStatus = $dataSet[static::APPROVAL_STATUS] ?? null;

        if (!$approvalStatus || !in_array($approvalStatus, static::ALLOWED_APPROVAL_STATUS_LIST)) {
            throw new InvalidDataException(sprintf(
                '"%s" should have one of the values: %s.',
                static::APPROVAL_STATUS,
                implode(', ', static::ALLOWED_APPROVAL_STATUS_LIST),
            ));
        }
    }
}
