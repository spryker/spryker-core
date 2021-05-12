<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step;

use Spryker\Shared\ProductOffer\ProductOfferConfig;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\MerchantProductOfferDataSetInterface;

class ApprovalStatusValidationStep implements DataImportStepInterface
{
    protected const APPROVAL_STATUS = MerchantProductOfferDataSetInterface::APPROVAL_STATUS;
    protected const ALLOWED_APPROVAL_STATUS_LIST = [
        ProductOfferConfig::STATUS_WAITING_FOR_APPROVAL,
        ProductOfferConfig::STATUS_APPROVED,
        ProductOfferConfig::STATUS_DENIED,
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
        $approvalStatus = $dataSet[static::APPROVAL_STATUS];

        if (!$approvalStatus) {
            throw new InvalidDataException('"' . static::APPROVAL_STATUS . '" is required.');
        }

        if (!in_array($approvalStatus, static::ALLOWED_APPROVAL_STATUS_LIST)) {
            throw new InvalidDataException('"' . static::APPROVAL_STATUS . '" should have one of the values: "' . implode(', ', static::ALLOWED_APPROVAL_STATUS_LIST) . '"');
        }
    }
}
