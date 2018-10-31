<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model;

use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationship;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery;
use Spryker\Shared\PriceProductMerchantRelationshipDataImport\PriceProductMerchantRelationshipDataImportConstants;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\DataSet\PriceProductMerchantRelationshipDataSetInterface;

class PriceProductMerchantRelationshipWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $priceProductMerchantRelationshipEntity = $this->findExistingPriceProductStoreEntity($dataSet);

        $idPriceProductStore = $dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRICE_PRODUCT_STORE];
        if ($priceProductMerchantRelationshipEntity
            && $priceProductMerchantRelationshipEntity->getFkPriceProductStore() === $idPriceProductStore) {
            return;
        }

        if (!$priceProductMerchantRelationshipEntity) {
            $priceProductMerchantRelationshipEntity = (new SpyPriceProductMerchantRelationship())
                ->setFkMerchantRelationship($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_MERCHANT_RELATIONSHIP]);

            if (!empty($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRODUCT_ABSTRACT])) {
                $priceProductMerchantRelationshipEntity->setFkProductAbstract($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRODUCT_ABSTRACT]);
            } else {
                $priceProductMerchantRelationshipEntity->setFkProduct($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRODUCT_CONCRETE]);
            }
        }

        $priceProductMerchantRelationshipEntity->setFkPriceProductStore($idPriceProductStore);

        $eventName = $priceProductMerchantRelationshipEntity->isNew()
            ? PriceProductMerchantRelationshipDataImportConstants::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_CREATE
            : PriceProductMerchantRelationshipDataImportConstants::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_UPDATE;

        $priceProductMerchantRelationshipEntity->save();

        $this->addPublishEvents(
            $eventName,
            (int)$priceProductMerchantRelationshipEntity->getPrimaryKey()
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationship|null
     */
    protected function findExistingPriceProductStoreEntity(DataSetInterface $dataSet): ?SpyPriceProductMerchantRelationship
    {
        $query = SpyPriceProductMerchantRelationshipQuery::create()
            ->usePriceProductStoreQuery()
                ->filterByFkStore($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_STORE])
                ->filterByFkCurrency($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_CURRENCY])
                ->filterByFkPriceProduct($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRICE_PRODUCT])
            ->endUse()
            ->filterByFkMerchantRelationship($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_MERCHANT_RELATIONSHIP]);

        if (!empty($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRODUCT_ABSTRACT])) {
            $query->filterByFkProductAbstract($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRODUCT_ABSTRACT]);
        } else {
            $query->filterByFkProduct($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRODUCT_CONCRETE]);
        }

        return $query->findOne();
    }
}
