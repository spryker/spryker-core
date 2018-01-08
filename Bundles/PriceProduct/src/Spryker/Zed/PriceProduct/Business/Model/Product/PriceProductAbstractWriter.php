<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class PriceProductAbstractWriter extends BaseProductPriceWriter implements PriceProductAbstractWriterInterface
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
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function persistProductAbstractPriceCollection(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->handleDatabaseTransaction(function () use ($productAbstractTransfer) {
            return $this->executePersistProductAbstractPriceCollectionTransaction($productAbstractTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function executePersistProductAbstractPriceCollectionTransaction(ProductAbstractTransfer $productAbstractTransfer)
    {
        $idProductAbstract = $productAbstractTransfer
            ->requireIdProductAbstract()
            ->getIdProductAbstract();

        foreach ($productAbstractTransfer->getPrices() as $priceProductTransfer) {
            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
            if ($this->isEmptyMoneyValue($moneyValueTransfer)) {
                continue;
            }

            $this->persistProductAbstractPriceEntity($priceProductTransfer, $idProductAbstract);
            $this->priceProductStoreWriter->persistPriceProductStore($priceProductTransfer);

            $priceProductTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceTransfer
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function persistProductAbstractPriceEntity(PriceProductTransfer $priceTransfer, $idProductAbstract)
    {
        $priceTypeEntity = $this->priceTypeReader->getPriceTypeByName($priceTransfer->getPriceType()->getName());

        $priceProductEntity = $this->priceProductQueryContainer
            ->queryPriceProductForAbstractProduct($idProductAbstract, $priceTypeEntity->getIdPriceType())
            ->findOneOrCreate();

        $priceProductEntity->setFkProductAbstract($idProductAbstract)
            ->save();

        $priceTransfer->setIdPriceProduct($priceProductEntity->getIdPriceProduct());

        return $priceTransfer;
    }
}
