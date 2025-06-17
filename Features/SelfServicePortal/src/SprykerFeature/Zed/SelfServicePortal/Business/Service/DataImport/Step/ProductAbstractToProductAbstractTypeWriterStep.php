<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\Step;

use Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractToProductAbstractTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\DataSet\ProductAbstractToProductAbstractTypeDataSetInterface;

class ProductAbstractToProductAbstractTypeWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractToProductAbstractTypeQuery $productAbstractToProductAbstractTypeQuery
     */
    public function __construct(
        protected SpyProductAbstractToProductAbstractTypeQuery $productAbstractToProductAbstractTypeQuery
    ) {
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $idProductAbstract = $dataSet[ProductAbstractToProductAbstractTypeDataSetInterface::ID_PRODUCT_ABSTRACT];
        $idProductAbstractType = $dataSet[ProductAbstractToProductAbstractTypeDataSetInterface::ID_PRODUCT_ABSTRACT_TYPE];

        $productAbstractToProductAbstractTypeEntity = $this->productAbstractToProductAbstractTypeQuery
            ->clear()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkProductAbstractType($idProductAbstractType)
            ->findOneOrCreate();

        if ($productAbstractToProductAbstractTypeEntity->isNew()) {
            $productAbstractToProductAbstractTypeEntity->save();

            $this->addPublishEvents(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $idProductAbstract);
        }
    }
}
