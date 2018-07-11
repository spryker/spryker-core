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
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriterInterface
     */
    protected $priceProductStoreWriter;

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface
     */
    protected $priceProductRepository;

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface
     */
    protected $priceProductEntityManager;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriterInterface $priceProductStoreWriter
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface $priceProductRepository
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface $priceProductEntityManager
     */
    public function __construct(
        PriceProductStoreWriterInterface $priceProductStoreWriter,
        PriceProductRepositoryInterface $priceProductRepository,
        PriceProductEntityManagerInterface $priceProductEntityManager
    ) {
        $this->priceProductStoreWriter = $priceProductStoreWriter;
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
        $priceProductTransfer = $this->priceProductStoreWriter->persistPriceProductStore($priceProductTransfer);
        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
        $idPriceProductDefault = $priceProductTransfer->getPriceDimension()->getIdPriceProductDefault();

        $priceProductDefaultEntity = $this->priceProductRepository->findPriceProductDefaultByIdPriceProductStore(
            $moneyValueTransfer->getIdEntity()
        );

        if ($priceProductDefaultEntity) {
            return $priceProductDefaultEntity;
        }

        $priceProductDefaultEntity = (new SpyPriceProductDefaultEntityTransfer())
            ->setIdPriceProductDefault($idPriceProductDefault)
            ->setFkPriceProductStore($moneyValueTransfer->getIdEntity());

         return $this->priceProductEntityManager
             ->savePriceProductDefaultEntity($priceProductDefaultEntity);
    }
}
