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
 * @method PriceBusinessFactory getFactory()
 */
class PriceFacade extends AbstractFacade
{

    /**
     * @return array
     */
    public function getPriceTypeValues()
    {
        return $this->getFactory()->getReaderModel()->getPriceTypes();
    }

    /**
     * @param string $sku
     * @param null $priceType
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceType = null)
    {
        return $this->getFactory()->getReaderModel()->getPriceBySku($sku, $priceType);
    }

    /**
     * @param string $name
     *
     * @return SpyPriceType
     */
    public function createPriceType($name)
    {
        return $this->getFactory()->getWriterModel()->createPriceType($name);
    }

    /**
     * @param PriceProductTransfer $transferPriceProduct
     *
     * @return mixed
     */
    public function setPriceForProduct(PriceProductTransfer $transferPriceProduct)
    {
        return $this->getFactory()->getWriterModel()->setPriceForProduct($transferPriceProduct);
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger)
    {
        $this->getFactory()->getInstaller($messenger)->install();
    }

    /**
     * @param string $sku
     * @param null $priceType
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceType = null)
    {
        return $this->getFactory()->getReaderModel()->hasValidPrice($sku, $priceType);
    }

    /**
     * @param PriceProductTransfer $transferPriceProduct
     *
     * @return void
     */
    public function createPriceForProduct(PriceProductTransfer $transferPriceProduct)
    {
        $this->getFactory()->getWriterModel()->createPriceForProduct($transferPriceProduct);
    }

    /**
     * @return string
     */
    public function getDefaultPriceTypeName()
    {
        return $this->getFactory()->getConfig()->getPriceTypeDefaultName();
    }

    /**
     * @param string $sku
     * @param string $priceType
     *
     * @return int
     */
    public function getIdPriceProduct($sku, $priceType)
    {
        return $this->getFactory()->getReaderModel()->getProductPriceIdBySku($sku, $priceType);
    }

}
