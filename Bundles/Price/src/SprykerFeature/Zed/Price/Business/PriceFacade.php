<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Price\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\Price\Persistence\SpyPriceType;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;

/**
 * @method PriceDependencyContainer getDependencyContainer()
 */
class PriceFacade extends AbstractFacade
{

    /**
     * @return array
     */
    public function getPriceTypeValues()
    {
        return $this->getDependencyContainer()->getReaderModel()->getPriceTypes();
    }

    /**
     * @param string $sku
     * @param null $priceType
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceType = null)
    {
        return $this->getDependencyContainer()->getReaderModel()->getPriceBySku($sku, $priceType);
    }

    /**
     * @param string $name
     *
     * @return SpyPriceType
     */
    public function createPriceType($name)
    {
        return $this->getDependencyContainer()->getWriterModel()->createPriceType($name);
    }

    /**
     * @param PriceProductTransfer $transferPriceProduct
     *
     * @return mixed
     */
    public function setPriceForProduct(PriceProductTransfer $transferPriceProduct)
    {
        return $this->getDependencyContainer()->getWriterModel()->setPriceForProduct($transferPriceProduct);
    }

    /**
     * @param MessengerInterface $messenger
     */
    public function install(MessengerInterface $messenger)
    {
        $this->getDependencyContainer()->getInstaller($messenger)->install();
    }

    /**
     * @param string $sku
     * @param null $priceType
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceType = null)
    {
        return $this->getDependencyContainer()->getReaderModel()->hasValidPrice($sku, $priceType);
    }

    /**
     * @param PriceProductTransfer $transferPriceProduct
     */
    public function createPriceForProduct(PriceProductTransfer $transferPriceProduct)
    {
        $this->getDependencyContainer()->getWriterModel()->createPriceForProduct($transferPriceProduct);
    }

    /**
     * @return string
     */
    public function getDefaultPriceTypeName()
    {
        return $this->getDependencyContainer()->getConfig()->getPriceTypeDefaultName();
    }

    /**
     * @param string $sku
     * @param string $priceType
     *
     * @return int
     */
    public function getIdPriceProduct($sku, $priceType)
    {
        return $this->getDependencyContainer()->getReaderModel()->getProductPriceIdBySku($sku, $priceType);
    }

}
