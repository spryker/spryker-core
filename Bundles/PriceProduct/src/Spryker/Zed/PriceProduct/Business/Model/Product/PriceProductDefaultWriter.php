<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer;
use Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface;

class PriceProductDefaultWriter implements PriceProductDefaultWriterInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface
     */
    protected $priceProductRepository;

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface
     */
    protected $priceProductEntityManager;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface $priceProductRepository
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface $priceProductEntityManager
     */
    public function __construct(
        PriceProductRepositoryInterface $priceProductRepository,
        PriceProductEntityManagerInterface $priceProductEntityManager
    ) {
        $this->priceProductRepository = $priceProductRepository;
        $this->priceProductEntityManager = $priceProductEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer
     */
    public function persistPriceProductDefault(PriceProductTransfer $priceProductTransfer): SpyPriceProductDefaultEntityTransfer
    {
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $priceProductTransfer->requireMoneyValue()->getMoneyValue();
        /** @var \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceDimensionTransfer */
        $priceDimensionTransfer = $priceProductTransfer->requirePriceDimension()->getPriceDimension();
        /** @var int $idPriceProductDefault */
        $idPriceProductDefault = $priceDimensionTransfer->getIdPriceProductDefault();
        /** @var int $idEntity */
        $idEntity = $moneyValueTransfer->getIdEntity();

        $priceProductDefaultEntity = $this->priceProductRepository->findPriceProductDefaultByIdPriceProductStore(
            $idEntity
        );

        if ($priceProductDefaultEntity) {
            return $priceProductDefaultEntity;
        }

        $idPriceProductDefault = $idPriceProductDefault ? (string)$idPriceProductDefault : null;
        $idEntity = $idEntity ? (string)$idEntity : null;

        $priceProductDefaultEntity = (new SpyPriceProductDefaultEntityTransfer())
            ->setIdPriceProductDefault($idPriceProductDefault)
            ->setFkPriceProductStore($idEntity);

        return $this->priceProductEntityManager
            ->savePriceProductDefaultEntity($priceProductDefaultEntity);
    }
}
