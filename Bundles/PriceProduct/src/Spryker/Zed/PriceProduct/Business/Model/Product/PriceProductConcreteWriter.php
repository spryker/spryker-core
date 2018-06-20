<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\PriceProduct\PriceProductConstants;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface;
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
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductDefaultWriterInterface
     */
    protected $priceProductDefaultWriter;

    /**
     * @var array|\Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionConcreteSaverPluginInterface[]
     */
    protected $priceDimensionConcreteSaverPlugins;

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface
     */
    protected $priceProductEntityManager;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface $priceTypeReader
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $priceProductQueryContainer
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductDefaultWriterInterface $priceProductDefaultWriter
     * @param \Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionConcreteSaverPluginInterface[] $priceDimensionConcreteSaverPlugins
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface $priceProductEntityManager
     */
    public function __construct(
        PriceProductTypeReaderInterface $priceTypeReader,
        PriceProductQueryContainerInterface $priceProductQueryContainer,
        PriceProductDefaultWriterInterface $priceProductDefaultWriter,
        array $priceDimensionConcreteSaverPlugins,
        PriceProductEntityManagerInterface $priceProductEntityManager
    ) {
        $this->priceTypeReader = $priceTypeReader;
        $this->priceProductQueryContainer = $priceProductQueryContainer;
        $this->priceProductDefaultWriter = $priceProductDefaultWriter;
        $this->priceDimensionConcreteSaverPlugins = $priceDimensionConcreteSaverPlugins;
        $this->priceProductEntityManager = $priceProductEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductConcretePriceCollection(
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer {

        return $this->handleDatabaseTransaction(function () use ($productConcreteTransfer) {
            return $this->executePersistProductConcretePriceCollectionTransaction($productConcreteTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function executePersistProductConcretePriceCollectionTransaction(
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer {
        $idProductConcrete = $productConcreteTransfer
            ->requireIdProductConcrete()
            ->getIdProductConcrete();

        foreach ($productConcreteTransfer->getPrices() as $priceProductTransfer) {
            $priceProductTransfer->requirePriceDimension();

            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
            if ($this->isEmptyMoneyValue($moneyValueTransfer)) {
                continue;
            }

            $this->persistProductConcretePriceEntity($priceProductTransfer, $idProductConcrete);

            $priceProductTransfer->setIdProduct($idProductConcrete);
            $priceProductTransfer = $this->executePriceDimensionConcreteSaverPlugins($priceProductTransfer);

            $priceProductTransfer->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract());
            $priceProductTransfer->setIdProduct($productConcreteTransfer->getIdProductConcrete());
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function executePriceDimensionConcreteSaverPlugins(
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {

        $priceDimensionType = $priceProductTransfer->getPriceDimension()->getType();
        if ($priceDimensionType === PriceProductConstants::PRICE_DIMENSION_DEFAULT) {
            $priceProductDefaultEntityTransfer = $this->priceProductDefaultWriter->persistPriceProductDefault($priceProductTransfer);
            $priceProductTransfer->getPriceDimension()->setIdPriceProductDefault(
                $priceProductDefaultEntityTransfer->getIdPriceProductDefault()
            );

            return $priceProductTransfer;
        }

        foreach ($this->priceDimensionConcreteSaverPlugins as $priceDimensionConcreteSaverPlugin) {
            if ($priceDimensionConcreteSaverPlugin->getDimensionName() !== $priceDimensionType) {
                continue;
            }
            return $priceDimensionConcreteSaverPlugin->savePrice($priceProductTransfer);
        }

        return $priceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function persistProductConcretePriceEntity(
        PriceProductTransfer $priceProductTransfer,
        $idProductConcrete
    ): PriceProductTransfer {

        $priceTypeEntity = $this->priceTypeReader->getPriceTypeByName($priceProductTransfer->getPriceType()->getName());

        $priceProductEntity = $this->priceProductQueryContainer
            ->queryPriceProductForConcreteProductBy($idProductConcrete, $priceTypeEntity->getIdPriceType())
            ->findOneOrCreate();

        $priceProductEntity
            ->setFkProduct($idProductConcrete)
            ->save();

        $priceProductTransfer->setIdPriceProduct($priceProductEntity->getIdPriceProduct());

        return $priceProductTransfer;
    }
}
