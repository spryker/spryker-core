<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipDataImport\Business\Model;

use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnit;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantRelationshipDataImport\Business\Model\DataSet\MerchantRelationshipDataSetInterface;

class MerchantRelationshipWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $merchantRelationshipEntity = SpyMerchantRelationshipQuery::create()
            ->filterByMerchantRelationshipKey($dataSet[MerchantRelationshipDataSetInterface::MERCHANT_RELATIONSHIP_KEY])
            ->findOneOrCreate();

        $merchantRelationshipEntity
            ->setFkMerchant($dataSet[MerchantRelationshipDataSetInterface::ID_MERCHANT])
            ->setFkCompanyBusinessUnit($dataSet[MerchantRelationshipDataSetInterface::ID_COMPANY_BUSINESS_UNIT])
            ->save();

        $this->saveMerchantRelationshipToCompanyBusinessUnit($dataSet, $merchantRelationshipEntity->getPrimaryKey());
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    protected function saveMerchantRelationshipToCompanyBusinessUnit(DataSetInterface $dataSet, int $idMerchantRelationship): void
    {
        SpyMerchantRelationshipToCompanyBusinessUnitQuery::create()
            ->filterByFkMerchantRelationship($idMerchantRelationship)
            ->deleteAll();

        foreach ($dataSet[MerchantRelationshipDataSetInterface::ID_COMPANY_BUSINESS_UNIT_ASSIGNEE_COLLECTION] as $idCompanyBusinessUnit) {
            (new SpyMerchantRelationshipToCompanyBusinessUnit())
                ->setFkMerchantRelationship($idMerchantRelationship)
                ->setFkCompanyBusinessUnit($idCompanyBusinessUnit)
                ->save();
        }
    }
}
