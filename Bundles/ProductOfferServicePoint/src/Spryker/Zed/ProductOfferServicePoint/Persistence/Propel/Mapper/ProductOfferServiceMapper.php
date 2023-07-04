<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicesTransfer;
use Generated\Shared\Transfer\ProductOfferServiceTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Orm\Zed\ProductOfferServicePoint\Persistence\Base\SpyProductOfferService;
use Orm\Zed\ProductOfferServicePoint\Persistence\Map\SpyProductOfferServiceTableMap;
use Propel\Runtime\Collection\ArrayCollection;

class ProductOfferServiceMapper
{
    /**
     * @uses \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepository::SERVICE_IDS_GROUPED
     *
     * @var string
     */
    protected const SERVICE_IDS_GROUPED = 'SERVICE_IDS_GROUPED';

    /**
     * @param \Propel\Runtime\Collection\ArrayCollection<string, mixed> $productOfferServicesData
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer
     */
    public function mapProductOfferServiceDataToProductOfferServiceCollectionTransfer(
        ArrayCollection $productOfferServicesData,
        ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
    ): ProductOfferServiceCollectionTransfer {
        foreach ($productOfferServicesData as $productOfferServiceData) {
            $productOfferServiceCollectionTransfer->addProductOfferServices($this->mapProductOfferServiceDataToProductOfferServicesTransfer(
                $productOfferServiceData,
                new ProductOfferServicesTransfer(),
            ));
        }

        return $productOfferServiceCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceTransfer $productOfferServiceTransfer
     * @param \Orm\Zed\ProductOfferServicePoint\Persistence\Base\SpyProductOfferService $productOfferServiceEntity
     *
     * @return \Orm\Zed\ProductOfferServicePoint\Persistence\Base\SpyProductOfferService
     */
    public function mapProductOfferServiceTransferToProductOfferServiceEntity(
        ProductOfferServiceTransfer $productOfferServiceTransfer,
        SpyProductOfferService $productOfferServiceEntity
    ): SpyProductOfferService {
        return $productOfferServiceEntity
            ->setFkProductOffer($productOfferServiceTransfer->getIdProductOfferOrFail())
            ->setFkService($productOfferServiceTransfer->getIdServiceOrFail());
    }

    /**
     * @param \Orm\Zed\ProductOfferServicePoint\Persistence\Base\SpyProductOfferService $productOfferServiceEntity
     * @param \Generated\Shared\Transfer\ProductOfferServiceTransfer $productOfferServiceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceTransfer
     */
    public function mapProductOfferServiceEntityToProductOfferServiceTransfer(
        SpyProductOfferService $productOfferServiceEntity,
        ProductOfferServiceTransfer $productOfferServiceTransfer
    ): ProductOfferServiceTransfer {
        return $productOfferServiceTransfer
            ->setIdProductOffer($productOfferServiceEntity->getFkProductOffer())
            ->setIdService($productOfferServiceEntity->getFkService());
    }

    /**
     * @param array<string, mixed> $productOfferServiceData
     * @param \Generated\Shared\Transfer\ProductOfferServicesTransfer $productOfferServicesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicesTransfer
     */
    public function mapProductOfferServiceDataToProductOfferServicesTransfer(
        array $productOfferServiceData,
        ProductOfferServicesTransfer $productOfferServicesTransfer
    ): ProductOfferServicesTransfer {
        $productOfferServicesTransfer->setProductOffer(
            (new ProductOfferTransfer())->setIdProductOffer((int)$productOfferServiceData[SpyProductOfferServiceTableMap::COL_FK_PRODUCT_OFFER]),
        );

        if (!isset($productOfferServiceData[static::SERVICE_IDS_GROUPED])) {
            return $productOfferServicesTransfer
                ->setIdProductOfferService((int)$productOfferServiceData[SpyProductOfferServiceTableMap::COL_ID_PRODUCT_OFFER_SERVICE])
                ->addService((new ServiceTransfer())->setIdService((int)$productOfferServiceData[SpyProductOfferServiceTableMap::COL_FK_SERVICE]));
        }

        $serviceIds = explode(',', $productOfferServiceData[static::SERVICE_IDS_GROUPED]);
        foreach ($serviceIds as $idService) {
            $productOfferServicesTransfer->addService(
                (new ServiceTransfer())->setIdService((int)$idService),
            );
        }

        return $productOfferServicesTransfer;
    }
}
