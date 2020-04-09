<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelation;

use Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductRelationDataImport\Business\Exception\DuplicatedQuerySetDataException;
use Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelation\DataSet\ProductRelationDataSetInterface;
use Spryker\Zed\ProductRelationDataImport\Dependency\Service\ProductRelationDataImportToUtilEncodingServiceInterface;

class QuerySetValidatorStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\ProductRelationDataImport\Dependency\Service\ProductRelationDataImportToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductRelationDataImport\Dependency\Service\ProductRelationDataImportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductRelationDataImportToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\ProductRelationDataImport\Business\Exception\DuplicatedQuerySetDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $querySet = $dataSet[ProductRelationDataSetInterface::COL_RULE];
        $querySetDecoded = $this->utilEncodingService->decodeJson($querySet, true);
        $productRelationEntities = SpyProductRelationQuery::create()
            ->filterByFkProductAbstract($dataSet[ProductRelationDataSetInterface::COL_ID_PRODUCT_ABSTRACT])
            ->filterByProductRelationKey($dataSet[ProductRelationDataSetInterface::COL_PRODUCT_RELATION_KEY], Criteria::NOT_EQUAL)
            ->useSpyProductRelationTypeQuery()
                ->filterByKey($dataSet[ProductRelationDataSetInterface::COL_RELATION_TYPE])
            ->endUse()
            ->find();

        foreach ($productRelationEntities as $productRelationEntity) {
            $existingQuerySetDecoded = $this->utilEncodingService->decodeJson($productRelationEntity->getQuerySetData(), true);
            if ($existingQuerySetDecoded == $querySetDecoded) {
                throw new DuplicatedQuerySetDataException('This query set data is already in use for the same product and relation type');
            }
        }
    }
}
