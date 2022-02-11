<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductApprovalDataImport\Business\Step;

use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductApprovalDataImport\Business\DataSet\MerchantProductApprovalStatusDefaultDataSetInterface;

class MerchantProductApprovalStatusDefaultWriterStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected const MERCHANT_REFERENCE = MerchantProductApprovalStatusDefaultDataSetInterface::MERCHANT_REFERENCE;

    /**
     * @var string
     */
    protected const APPROVAL_STATUS = MerchantProductApprovalStatusDefaultDataSetInterface::APPROVAL_STATUS;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $merchantReference = $dataSet[static::MERCHANT_REFERENCE];
        $approvalStatus = $dataSet[static::APPROVAL_STATUS];

        if (!$merchantReference) {
            throw new InvalidDataException(sprintf('"%s" is required.', static::MERCHANT_REFERENCE));
        }

        $merchantEntity = SpyMerchantQuery::create()
            ->filterByMerchantReference($merchantReference)
            ->findOne();

        if (!$merchantEntity) {
            throw new EntityNotFoundException(sprintf('Could not find Merchant by reference "%s".', $merchantReference));
        }

        $merchantEntity->setDefaultProductAbstractApprovalStatus($approvalStatus);
        $merchantEntity->save();
    }
}
