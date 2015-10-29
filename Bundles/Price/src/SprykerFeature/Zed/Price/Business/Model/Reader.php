<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Price\Business\Model;

use SprykerFeature\Zed\Price\Dependency\Facade\PriceToProductInterface;
use SprykerFeature\Zed\Price\Persistence\PriceQueryContainer;
use Orm\Zed\Price\Persistence\SpyPriceProduct;
use Orm\Zed\Price\Persistence\SpyPriceType;
use SprykerFeature\Zed\Price\PriceConfig;
use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;

class Reader implements ReaderInterface
{

    const PRICE_TYPE_UNKNOWN = 'price type unknown: ';
    const NO_RESULT = 'no result';
    const SKU_UNKNOWN = 'sku unknown';

    /**
     * @var PriceQueryContainer
     */
    protected $queryContainer;

    /**
     * @var PriceToProductInterface
     */
    protected $productFacade;

    /**
     * @var PriceConfig
     */
    protected $priceSettings;

    /**
     * @var array
     */
    protected $priceTypeEntityByNameCache = array();

    /**
     * @param PriceQueryContainer $queryContainer
     * @param PriceToProductInterface $productFacade
     * @param PriceConfig $priceSettings
     */
    public function __construct(
        $queryContainer,
        $productFacade,
        $priceSettings
    ) {
        $this->queryContainer = $queryContainer;
        $this->productFacade = $productFacade;
        $this->priceSettings = $priceSettings;
    }

    /**
     * @return array
     */
    public function getPriceTypes()
    {
        $priceTypes = [];
        $priceTypeEntities = $this->queryContainer->queryAllPriceTypes()->find();

        /** @var SpyPriceType $priceType */
        foreach ($priceTypeEntities as $priceType) {
            $priceTypes[] = $priceType->getName();
        }

        return $priceTypes;
    }

    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @throws \Exception
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceTypeName = null)
    {
        $priceTypeName = $this->handleDefaultPriceType($priceTypeName);
        $priceEntity = $this->getPriceEntity($sku, $this->getPriceTypeByName($priceTypeName));

        return $priceEntity->getPrice();
    }

    /**
     * @param string $priceTypeName
     *
     * @throws \Exception
     *
     * @return SpyPriceType
     */
    public function getPriceTypeByName($priceTypeName)
    {
        if (!isset($this->priceTypeEntityByNameCache[$priceTypeName])) {
            $priceTypeEntity = $this->queryContainer->queryPriceType($priceTypeName)->findOne();
            if (null === $priceTypeEntity) {
                throw new \Exception(self::PRICE_TYPE_UNKNOWN . $priceTypeName);
            }

            $this->priceTypeEntityByNameCache[$priceTypeName] = $priceTypeEntity;
        }

        return $this->priceTypeEntityByNameCache[$priceTypeName];
    }

    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceTypeName = null)
    {
        $priceTypeName = $this->handleDefaultPriceType($priceTypeName);
        $priceType = $this->getPriceTypeByName($priceTypeName);

        if ($this->hasPriceForConcreteProduct($sku, $priceType)
            || $this->hasPriceForAbstractProduct($sku, $priceType)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasConcreteProduct($sku)
    {
        return $this->productFacade->hasConcreteProduct($sku);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasAbstractProduct($sku)
    {
        return $this->productFacade->hasAbstractProduct($sku);
    }

    /**
     * @param string $sku
     * @param string $priceTypeName
     *
     * @throws \Exception
     *
     * @return int
     */
    public function getProductPriceIdBySku($sku, $priceTypeName)
    {
        $priceType = $this->getPriceTypeByName($priceTypeName);

        if ($this->hasPriceForConcreteProduct($sku, $priceType)) {
            return $this->queryContainer
                ->queryPriceEntityForConcreteProduct($sku, $priceType)
                ->findOne()
                ->getIdPriceProduct()
            ;
        } else {
            return $this->queryContainer
                ->queryPriceEntityForConcreteProduct($sku, $priceType)
                ->findOne()
                ->getIdPriceProduct()
            ;
        }
    }

    /**
     * @param string $sku
     * @param SpyPriceType $priceType
     *
     * @throws \Exception
     *
     * @return SpyPriceProduct
     */
    protected function getPriceEntity($sku, SpyPriceType $priceType)
    {
        if ($this->hasPriceForConcreteProduct($sku, $priceType)) {
            return $this->getPriceEntityForConcreteProduct($sku, $priceType);
        }
        if ($this->hasPriceForAbstractProduct($sku, $priceType)) {
            return $this->getPriceEntityForAbstractProduct($sku, $priceType);
        }
        $abstractSku = $this->productFacade->getAbstractSkuFromConcreteProduct($sku);
        if ($this->hasAbstractProduct($sku)
            && $this->hasPriceForAbstractProduct($abstractSku, $priceType)
        ) {
            return $this->getPriceEntityForAbstractProduct($abstractSku, $priceType);
        }
        throw new \Exception(self::NO_RESULT);
    }

    /**
     * @param string $sku
     * @param SpyPriceType $priceType
     *
     * @return bool
     */
    protected function hasPriceForConcreteProduct($sku, SpyPriceType $priceType)
    {
        $priceProductCount = $this->queryContainer
            ->queryPriceEntityForConcreteProduct($sku, $priceType)
            ->count()
        ;

        return $priceProductCount > 0;
    }

    /**
     * @param string $sku
     * @param SpyPriceType $priceType
     *
     * @return bool
     */
    protected function hasPriceForAbstractProduct($sku, $priceType)
    {
        $priceProductCount = $this->queryContainer
            ->queryPriceEntityForAbstractProduct($sku, $priceType)
            ->count()
        ;

        return $priceProductCount > 0;
    }

    /**
     * @param string $sku
     * @param SpyPriceType $priceType
     *
     * @return SpyPriceProduct
     */
    protected function getPriceEntityForConcreteProduct($sku, $priceType)
    {
        return $this->queryContainer
            ->queryPriceEntityForConcreteProduct($sku, $priceType)
            ->findOne()
            ;
    }

    /**
     * @param string $sku
     * @param SpyPriceType $priceType
     *
     * @return SpyPriceProduct
     */
    protected function getPriceEntityForAbstractProduct($sku, $priceType)
    {
        return $this->queryContainer
            ->queryPriceEntityForAbstractProduct($sku, $priceType)
            ->findOne()
            ;
    }

    /**
     * @param string $priceType
     *
     * @return SpyPriceType
     */
    protected function handleDefaultPriceType($priceType = null)
    {
        if (null === $priceType) {
            $priceType = $this->priceSettings->getPriceTypeDefaultName();
        }

        return $priceType;
    }
    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getAbstractProductIdBySku($sku)
    {
        return $this->productFacade->getAbstractProductIdBySku($sku);
    }

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getConcreteProductIdBySku($sku)
    {
        return $this->productFacade->getConcreteProductIdBySku($sku);
    }

}
