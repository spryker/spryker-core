<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Business\Model;

use Spryker\Zed\Price\Persistence\PriceQueryContainer;
use Spryker\Zed\Price\Dependency\Facade\PriceToTouchInterface;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\Price\Persistence\SpyPriceProduct;
use Orm\Zed\Price\Persistence\SpyPriceType;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Price\PriceConfig;
use Spryker\Zed\Price\Business\Exception\ProductPriceChangeException;

class Writer implements WriterInterface
{

    const TOUCH_PRODUCT = 'product';
    const ENTITY_NOT_FOUND = 'entity not found';

    /**
     * @var PriceQueryContainer
     */
    protected $queryContainer;

    /**
     * @var ReaderInterface
     */
    protected $reader;

    /**
     * @var PriceToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var PriceConfig
     */
    protected $priceSettings;

    /**
     * @param PriceQueryContainer $queryContainer
     * @param ReaderInterface $reader
     * @param PriceToTouchInterface $touchFacade
     * @param PriceConfig $priceSettings
     */
    public function __construct(
        PriceQueryContainer $queryContainer,
        ReaderInterface $reader,
        PriceToTouchInterface $touchFacade,
        PriceConfig $priceSettings
    ) {
        $this->queryContainer = $queryContainer;
        $this->reader = $reader;
        $this->touchFacade = $touchFacade;
        $this->priceSettings = $priceSettings;
    }

    /**
     * @param string $name
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceType
     */
    public function createPriceType($name)
    {
        $priceTypeEntity = $this->queryContainer->queryPriceType($name)->findOneOrCreate();
        $priceTypeEntity->setName($name)->save();

        return $priceTypeEntity;
    }

    /**
     * @param PriceProductTransfer $priceProductTransfer
     *
     * @throws \Spryker\Zed\Price\Business\Exception\ProductPriceChangeException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProduct
     */
    public function createPriceForProduct(PriceProductTransfer $priceProductTransfer)
    {
        $priceProductTransfer = $this->setPriceType($priceProductTransfer);
        if (
            !$this->isPriceTypeExistingForProductAbstract($priceProductTransfer)
            && !$this->isPriceTypeExistingForProductConcrete($priceProductTransfer)
        ) {
            $this->loadProductAbstractIdForPriceProductTransfer($priceProductTransfer);
            $this->loadProductConcreteIdForPriceProductTransfer($priceProductTransfer);

            $entity = new SpyPriceProduct();
            $newPrice = $this->savePriceProductEntity($priceProductTransfer, $entity);

            if ($priceProductTransfer->getIdProduct()) {
                $this->insertTouchRecord(self::TOUCH_PRODUCT, $priceProductTransfer->getIdProduct());
            }

            return $newPrice;
        }
        throw new ProductPriceChangeException('This couple product price type is already set');
    }

    /**
     * @param PriceProductTransfer $priceProductTransfer
     *
     * @throws \Spryker\Zed\Price\Business\Exception\ProductPriceChangeException
     *
     * @return void
     */
    public function setPriceForProduct(PriceProductTransfer $priceProductTransfer)
    {
        $priceProductTransfer = $this->setPriceType($priceProductTransfer);

        if (
            $this->isPriceTypeExistingForProductConcrete($priceProductTransfer)
            || $this->isPriceTypeExistingForProductAbstract($priceProductTransfer)
        ) {
            $this->loadProductAbstractIdForPriceProductTransfer($priceProductTransfer);
            $this->loadProductConcreteIdForPriceProductTransfer($priceProductTransfer);

            $priceProductEntity = $this->getPriceProductById($priceProductTransfer->getIdPriceProduct());
            $this->savePriceProductEntity($priceProductTransfer, $priceProductEntity);

            if ($priceProductTransfer->getIdProduct()) {
                $this->insertTouchRecord(self::TOUCH_PRODUCT, $priceProductTransfer->getIdProduct());
            }
        } else {
            throw new ProductPriceChangeException('There is no price assigned for selected product!');
        }
    }

    /**
     * @param PriceProductTransfer $transferPriceProduct
     *
     * @return void
     */
    protected function loadProductAbstractIdForPriceProductTransfer(PriceProductTransfer $transferPriceProduct)
    {
        if ($transferPriceProduct->getIdProductAbstract() === null) {
            $transferPriceProduct->setIdProductAbstract(
                $this->reader->getProductAbstractIdBySku($transferPriceProduct->getSkuProductAbstract())
            );
        }
    }

    /**
     * @param PriceProductTransfer $transferPriceProduct
     *
     * @return void
     */
    protected function loadProductConcreteIdForPriceProductTransfer(PriceProductTransfer $transferPriceProduct)
    {
        if (
            $transferPriceProduct->getIdProduct() === null &&
            $this->reader->hasProductConcrete($transferPriceProduct->getSkuProduct())
        ) {
            $transferPriceProduct->setIdProduct(
                $this->reader->getProductConcreteIdBySku($transferPriceProduct->getSkuProduct())
            );
        }
    }

    /**
     * @param PriceProductTransfer $transferPriceProduct
     * @param SpyPriceProduct $priceProductEntity
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProduct
     */
    protected function savePriceProductEntity(PriceProductTransfer $transferPriceProduct, SpyPriceProduct $priceProductEntity)
    {
        $priceType = $this->reader->getPriceTypeByName($transferPriceProduct->getPriceTypeName());
        $priceProductEntity
            ->setPriceType($priceType)
            ->setPrice($transferPriceProduct->getPrice());

        if ($transferPriceProduct->getIdProduct()) {
            $priceProductEntity->setFkProduct($transferPriceProduct->getIdProduct());
        } else {
            $priceProductEntity->setFkProductAbstract($transferPriceProduct->getIdProductAbstract());
        }

        $priceProductEntity->save();

        return $priceProductEntity;
    }

    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return void
     */
    protected function insertTouchRecord($itemType, $itemId)
    {
        $this->touchFacade->touchActive($itemType, $itemId);
    }

    /**
     * @param PriceProductTransfer $transferPriceProduct
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function setPriceType(PriceProductTransfer $transferPriceProduct)
    {
        if ($transferPriceProduct->getPriceTypeName() === null) {
            $transferPriceProduct->setPriceTypeName($this->priceSettings->getPriceTypeDefaultName());
        }

        return $transferPriceProduct;
    }

    /**
     * @param int $idPriceProduct
     *
     * @throws \Exception
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProduct
     */
    protected function getPriceProductById($idPriceProduct)
    {
        $priceProductEntity = $this->queryContainer->queryPriceProductEntity($idPriceProduct)->find();
        if (!count($priceProductEntity) > 0) {
            throw new \Exception(self::ENTITY_NOT_FOUND);
        }

        return $this->queryContainer->queryPriceProductEntity($idPriceProduct)->findOne();
    }

    /**
     * @param PriceProductTransfer $transferPriceProduct
     *
     * @return bool
     */
    protected function isPriceTypeExistingForProductAbstract(PriceProductTransfer $transferPriceProduct)
    {
        $priceType = $this->reader->getPriceTypeByName($transferPriceProduct->getPriceTypeName());
        $priceEntities = $this->queryContainer
            ->queryPriceEntityForProductAbstract($transferPriceProduct->getSkuProductAbstract(), $priceType);

        return $priceEntities->count() > 0;
    }

    /**
     * @param int $idProductConcrete
     * @param string $priceType
     * @param \DateTime $date
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProduct
     */
    protected function getPriceEntityForProductConcrete($idProductConcrete, $priceType, \DateTime $date)
    {
        $idPriceType = $this->reader->getPriceTypeByName($priceType)->getIdPriceType();

        return $this->queryContainer->queryPriceEntityForProductConcrete($idProductConcrete, $date, $idPriceType)->findOne();
    }

    /**
     * @param PriceProductTransfer $transferPriceProduct
     *
     * @return bool
     */
    protected function isPriceTypeExistingForProductConcrete(PriceProductTransfer $transferPriceProduct)
    {
        $priceType = $this->reader->getPriceTypeByName($transferPriceProduct->getPriceTypeName());
        $priceEntities = $this->queryContainer
            ->queryPriceEntityForProductConcrete($transferPriceProduct->getSkuProduct(), $priceType);

        return $priceEntities->count() > 0;
    }

}
