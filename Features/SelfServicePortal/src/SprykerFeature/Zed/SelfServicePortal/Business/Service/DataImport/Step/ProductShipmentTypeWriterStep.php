<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\Step;

use Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\DataSet\ProductShipmentTypeDataSetInterface;

class ProductShipmentTypeWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentTypeQuery $productShipmentTypeQuery
     */
    public function __construct(
        protected SpyProductShipmentTypeQuery $productShipmentTypeQuery
    ) {
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $idProduct = $dataSet[ProductShipmentTypeDataSetInterface::ID_PRODUCT];
        $idShipmentType = $dataSet[ProductShipmentTypeDataSetInterface::ID_SHIPMENT_TYPE];

        $productShipmentTypeEntity = $this->productShipmentTypeQuery
            ->clear()
            ->filterByFkProduct($idProduct)
            ->filterByFkShipmentType($idShipmentType)
            ->findOneOrCreate();

        $productShipmentTypeEntity->save();

        $this->addPublishEvents(ProductEvents::PRODUCT_CONCRETE_PUBLISH, $idProduct);
    }
}
