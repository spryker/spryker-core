<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class PriceProductConcreteWriter extends BaseProductPriceWriter implements PriceProductConcreteWriterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface
     */
    protected $priceTypeReader;

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface
     */
    protected $priceProductQueryContainer;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriterInterface
     */
    protected $priceProductStoreWriter;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface $priceTypeReader
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $priceProductQueryContainer
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriterInterface $priceProductStoreWriter
     */
    public function __construct(
        PriceProductTypeReaderInterface $priceTypeReader,
        PriceProductQueryContainerInterface $priceProductQueryContainer,
        PriceProductStoreWriterInterface $priceProductStoreWriter
    ) {
        $this->priceTypeReader = $priceTypeReader;
        $this->priceProductQueryContainer = $priceProductQueryContainer;
        $this->priceProductStoreWriter = $priceProductStoreWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductConcretePriceCollection(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->handleDatabaseTransaction(function () use ($productConcreteTransfer) {
            return $this->executePersistProductConcretePriceCollectionTransaction($productConcreteTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function executePersistProductConcretePriceCollectionTransaction(ProductConcreteTransfer $productConcreteTransfer)
    {
        $idProductConcrete = $productConcreteTransfer
            ->requireIdProductConcrete()
            ->getIdProductConcrete();

        foreach ($productConcreteTransfer->getPrices() as $priceProductTransfer) {
            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
            if ($this->isEmptyMoneyValue($moneyValueTransfer)) {
                continue;
            }

            $this->persistProductConcretePriceEntity($priceProductTransfer, $idProductConcrete);
            $this->priceProductStoreWriter->persistPriceProductStore($priceProductTransfer);

            $priceProductTransfer->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract());
            $priceProductTransfer->setIdProduct($productConcreteTransfer->getIdProductConcrete());
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function persistProductConcretePriceEntity(PriceProductTransfer $priceProductTransfer, $idProductConcrete)
    {
        $priceTypeEntity = $this->priceTypeReader->getPriceTypeByName($priceProductTransfer->getPriceType()->getName());

        $priceProductEntity = $this->priceProductQueryContainer
            ->queryPriceProductForConcreteProductBy($idProductConcrete, $priceTypeEntity->getIdPriceType())
            ->findOneOrCreate();

        $priceProductEntity->setFkProduct($idProductConcrete)->save();

        $priceProductTransfer->setIdPriceProduct($priceProductEntity->getIdPriceProduct());

        return $priceProductTransfer;
    }
}
