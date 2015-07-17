<?php

namespace SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Business\Model;

use SprykerFeature\Zed\Price\Business\PriceFacade;

class Helper implements HelperInterface
{

    /**
     * @var PriceFacade
     */
    protected $priceFacade;

    /**
     * @param PriceFacade $priceFacade
     */
    public function __construct(PriceFacade $priceFacade)
    {
        $this->priceFacade = $priceFacade;
    }

    /**
     * @param array $entity
     * @return string
     */
    public function organizeData(array $entity)
    {
        $organizedPrices = [];
        $priceTypes = explode(',', $entity['price_types']);
        $prices = explode(',', $entity['concrete_prices']);
//        $isActive = explode(',', $entity['is_active']);

        foreach ($prices as $index => $price) {
            $organizedPrices[$priceTypes[$index]]['price'] = $price;
//            $organizedPrices[$priceTypes[$index]]['is_active'] = $isActive[$index];
        }

        return $organizedPrices;
    }

    /**
     * @param array $entity
     * @return int
     */
    public function getDefaultPrice(array $entity)
    {
        $priceTypes = explode(',', $entity['price_types']);
        $prices = explode(',', $entity['concrete_prices']);

        foreach ($priceTypes as $index => $priceType) {
            if ($priceType == $this->getDefaultPriceType()) {
                return $prices[$index];
            }
        }
    }

    /**
     * @param array $entity
     * @return bool
     */
    public function hasDefaultPrice(array $entity)
    {
        $priceTypes = explode(',', $entity['price_types']);
        foreach ($priceTypes as $priceType) {
            if ($priceType == $this->getDefaultPriceType()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getDefaultPriceType()
    {
        return $this->priceFacade->getDefaultPriceTypeName();
    }
}
