<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionExporter\Dependency\Facade;

class ProductOptionExporterToProductOptionBridge implements ProductOptionExporterToProductOptionInterface
{

    /**
     * @var \Spryker\Zed\ProductOption\Business\ProductOptionFacade
     */
    protected $productOptionFacade;

    /**
     * ProductOptionExporterToProductOptionBridge constructor.
     *
     * @param \Spryker\Zed\ProductOption\Business\ProductOptionFacade $productOptionFacade
     */
    public function __construct($productOptionFacade)
    {
        $this->productOptionFacade = $productOptionFacade;
    }

    /**
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return array
     */
    public function getTypeUsagesForConcreteProduct($idProduct, $idLocale)
    {
        return $this->productOptionFacade->getTypeUsagesForConcreteProduct($idProduct, $idLocale);
    }

    /**
     * @param int $idTypeUsage
     * @param int $idLocale
     *
     * @return array
     */
    public function getValueUsagesForTypeUsage($idTypeUsage, $idLocale)
    {
        return $this->productOptionFacade->getValueUsagesForTypeUsage($idTypeUsage, $idLocale);
    }

    /**
     * @param int $idTypeUsage
     *
     * @return array
     */
    public function getTypeExclusionsForTypeUsage($idTypeUsage)
    {
        return $this->productOptionFacade->getTypeExclusionsForTypeUsage($idTypeUsage);
    }

    /**
     * @param int $idValueUsage
     *
     * @return array
     */
    public function getValueConstraintsForValueUsage($idValueUsage)
    {
        return $this->productOptionFacade->getValueConstraintsForValueUsage($idValueUsage);
    }

    /**
     * @param int $idValueUsage
     * @param string $operator
     *
     * @return array
     */
    public function getValueConstraintsForValueUsageByOperator($idValueUsage, $operator)
    {
        return $this->productOptionFacade->getValueConstraintsForValueUsageByOperator($idValueUsage, $operator);
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function getConfigPresetsForConcreteProduct($idProduct)
    {
        return $this->productOptionFacade->getConfigPresetsForConcreteProduct($idProduct);
    }

    /**
     * @param int $idConfigPreset
     *
     * @return array
     */
    public function getValueUsagesForConfigPreset($idConfigPreset)
    {
        return $this->productOptionFacade->getValueUsagesForConfigPreset($idConfigPreset);
    }

    /**
     * @param int $idTypeUsage
     *
     * @return string|null
     */
    public function getEffectiveTaxRateForTypeUsage($idTypeUsage)
    {
        return $this->productOptionFacade->getEffectiveTaxRateForTypeUsage($idTypeUsage);
    }

}
