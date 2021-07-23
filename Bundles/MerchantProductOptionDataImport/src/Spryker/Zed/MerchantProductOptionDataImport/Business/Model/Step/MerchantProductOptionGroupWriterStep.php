<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOptionDataImport\Business\Model\Step;

use Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOptionDataImport\Business\Model\DataSet\MerchantProductOptionDataSetInterface;

class MerchantProductOptionGroupWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Shared\MerchantProductOptionStorage\MerchantProductOptionStorageConfig::MERCHANT_PRODUCT_OPTION_GROUP_PUBLISH
     */
    protected const EVENT_MERCHANT_PRODUCT_OPTION_GROUP_PUBLISH = 'MerchantProductOption.group.publish';

    /**
     * @phpstan-param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<mixed> $dataSet
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $merchantProductOptionGroupEntity = SpyMerchantProductOptionGroupQuery::create()
            ->filterByMerchantReference($dataSet[MerchantProductOptionDataSetInterface::MERCHANT_REFERENCE])
            ->findOneOrCreate();
        $merchantProductOptionGroupEntity->setFkProductOptionGroup($dataSet[MerchantProductOptionDataSetInterface::ID_PRODUCT_OPTION_GROUP]);
        $merchantProductOptionGroupEntity->setMerchantSku($dataSet[MerchantProductOptionDataSetInterface::MERCHANT_SKU] ?: null);
        $merchantProductOptionGroupEntity->setApprovalStatus($dataSet[MerchantProductOptionDataSetInterface::APPROVAL_STATUS]);
        $merchantProductOptionGroupEntity->save();

        $this->addPublishEvents(
            static::EVENT_MERCHANT_PRODUCT_OPTION_GROUP_PUBLISH,
            $merchantProductOptionGroupEntity->getIdMerchantProductOptionGroup()
        );
    }
}
