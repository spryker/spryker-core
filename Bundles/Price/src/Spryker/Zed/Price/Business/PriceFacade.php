<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\Price\Persistence\SpyPriceType;
use Spryker\Shared\Kernel\Messenger\MessengerInterface;

/**
 * @method PriceDependencyContainer getBusinessFactory()
 */
class PriceFacade extends AbstractFacade
{

    /**
     * @return array
     */
    public function getPriceTypeValues()
    {
        return $this->getBusinessFactory()->getReaderModel()->getPriceTypes();
    }

    /**
     * @param string $sku
     * @param null $priceType
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceType = null)
    {
        return $this->getBusinessFactory()->getReaderModel()->getPriceBySku($sku, $priceType);
    }

    /**
     * @param string $name
     *
     * @return SpyPriceType
     */
    public function createPriceType($name)
    {
        return $this->getBusinessFactory()->getWriterModel()->createPriceType($name);
    }

    /**
     * @param PriceProductTransfer $transferPriceProduct
     *
     * @return mixed
     */
    public function setPriceForProduct(PriceProductTransfer $transferPriceProduct)
    {
        return $this->getBusinessFactory()->getWriterModel()->setPriceForProduct($transferPriceProduct);
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger)
    {
        $this->getBusinessFactory()->getInstaller($messenger)->install();
    }

    /**
     * @param string $sku
     * @param null $priceType
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceType = null)
    {
        return $this->getBusinessFactory()->getReaderModel()->hasValidPrice($sku, $priceType);
    }

    /**
     * @param PriceProductTransfer $transferPriceProduct
     *
     * @return void
     */
    public function createPriceForProduct(PriceProductTransfer $transferPriceProduct)
    {
        $this->getBusinessFactory()->getWriterModel()->createPriceForProduct($transferPriceProduct);
    }

    /**
     * @return string
     */
    public function getDefaultPriceTypeName()
    {
        return $this->getBusinessFactory()->getConfig()->getPriceTypeDefaultName();
    }

    /**
     * @param string $sku
     * @param string $priceType
     *
     * @return int
     */
    public function getIdPriceProduct($sku, $priceType)
    {
        return $this->getBusinessFactory()->getReaderModel()->getProductPriceIdBySku($sku, $priceType);
    }

}
