<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionExporter\Business\Model;

use SprykerFeature\Zed\ProductOptionExporter\Dependency\Facade\ProductOptionExporterToProductOptionInterface;
use SprykerFeature\Zed\ProductOptionExporter\Dependency\Facade\ProductOptionExporterToProductInterface;
use SprykerFeature\Zed\ProductOptionExporter\Dependency\Facade\ProductOptionExporterToLocaleInterface;

class ExportProcessor implements ExportProcessorInterface
{

    const CONSTRAINT_ALLOW = 'ALLOW';

    const CONSTRAINT_NOT = 'NOT';

    const CONSTRAINT_ALWAYS = 'ALWAYS';

    /**
     * @var ProductOptionExporterToProductOptionInterface
     */
    protected $productOptionFacade;

    /**
     * @var ProductOptionExporterToProductInterface
     */
    protected $productFacade;

    /**
     * @var ProductOptionExporterToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param ProductOptionExporterToProductOptionInterface $productOptionFacade
     * @param ProductOptionExporterToProductInterface $productFacade
     * @param ProductOptionExporterToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductOptionExporterToProductOptionInterface $productOptionFacade,
        ProductOptionExporterToProductInterface $productFacade,
        ProductOptionExporterToLocaleInterface $localeFacade
    ) {
        $this->productOptionFacade = $productOptionFacade;
        $this->productFacade = $productFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     *
     * @return array
     */
    public function processDataForExport(array &$resultSet, array $processedResultSet)
    {
        foreach ($resultSet as $index => $productRawData) {
            if (isset($processedResultSet[$index], $processedResultSet[$index]['concrete_products'])) {
                $idLocale = $this->extractLocalIdFromStorateKey($index);
                $this->processVariants($processedResultSet[$index]['concrete_products'], $idLocale);
            }
        }

        return $processedResultSet;
    }

    /**
     * @param string $storageKey
     *
     * @return int
     */
    private function extractLocalIdFromStorateKey($storageKey)
    {
        $keyParts = explode('.', $storageKey);
        $localeTransfer = $this->localeFacade->getLocale($keyParts[1]);

        return $localeTransfer->getIdLocale();
    }

    /**
     * @param array $concreteProducts
     */
    protected function processVariants(array &$concreteProducts, $idLocale)
    {
        foreach ($concreteProducts as $index => $concreteProduct) {

            $idProduct = $this->productFacade->getConcreteProductIdBySku($concreteProduct['sku']);

            $concreteProducts[$index]['configs'] = $this->processConfigs($idProduct);
            $concreteProducts[$index]['options'] = $this->processOptions($concreteProduct['sku'], $idProduct, $idLocale);
        }
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    protected function processConfigs($idProduct)
    {
        $configs = [];
        $configPresets = $this->productOptionFacade->getConfigPresetsForConcreteProduct($idProduct);
        foreach ($configPresets as $configPreset) {
            $values = $this->productOptionFacade->getValueUsagesForConfigPreset($configPreset['presetId']);
            foreach($values as $index => $value) {
                $values[$index] = (int) $value;
            }
            $configs[] = [
                'values' => $values
            ];
        }

        return $configs;
    }

    /**
     * @param string $sku
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return array
     */
    protected function processOptions($sku, $idProduct, $idLocale)
    {
        $typeUsages = $this->productOptionFacade->getTypeUsagesForConcreteProduct($idProduct, $idLocale);

        $options = [];
        foreach ($typeUsages as $typeUsage) {
            $optionData = [
                'id' => (int) $typeUsage['idTypeUsage'],
                'label' => $typeUsage['label'],
                'isOptional' => (bool) $typeUsage['isOptional'],
                'taxRate' => $this->processOptionTaxRate($sku, $typeUsage['idTypeUsage']),
                'excludes' => $this->processTypeExclusions($typeUsage['idTypeUsage']),
                'values' => $this->processValuesForTypeUsage($typeUsage['idTypeUsage'], $idLocale)
            ];
            $options[] = $optionData;
        }

        return $options;
    }

    protected function processTypeExclusions($idTypeUsage)
    {
        $excludes = $this->productOptionFacade->getTypeExclusionsForTypeUsage($idTypeUsage);
        foreach ($excludes as $index => $exclude) {
            $excludes[$index] = (int) $exclude;
        }

        return $excludes;
    }

    /**
     * @param int $sku
     * @param int $idTypeUsage
     *
     * @return float
     */
    protected function processOptionTaxRate($sku, $idTypeUsage)
    {
        $typeUsageTaxRate = $this->productOptionFacade->getEffectiveTaxRateForTypeUsage($idTypeUsage);

        if (null === $typeUsageTaxRate) {
            $typeUsageTaxRate = $this->productFacade
               ->getEffectiveTaxRateForConcreteProduct($sku);
        }

        return (float) $typeUsageTaxRate;
    }

    /**
     * @param int $idTypeUsage
     * @param int $idLocale
     *
     * @return array
     */
    protected function processValuesForTypeUsage($idTypeUsage, $idLocale)
    {
        $valueUsages = $this->productOptionFacade->getValueUsagesForTypeUsage($idTypeUsage, $idLocale);

        $valueData = [];
        foreach ($valueUsages as $valueUsage) {
            $valueData[] = [
                'id' => (int) $valueUsage['idValueUsage'],
                'label' => $valueUsage['label'],
                'price' => $valueUsage['price'],
                'constraints' => $this->processValueConstraints($valueUsage['idValueUsage']),
            ];
        }

        return $valueData;
    }

    /**
     * @param int $idValueUsage
     *
     * @return array
     */
    protected function processValueConstraints($idValueUsage)
    {
        $constraints = [];

        $allowValues = $this->productOptionFacade
            ->getValueConstraintsForValueUsageByOperator($idValueUsage, self::CONSTRAINT_ALLOW);
        $this->processConstraintValues('allow', $allowValues, $constraints);

        $alwaysValues = $this->productOptionFacade
            ->getValueConstraintsForValueUsageByOperator($idValueUsage, self::CONSTRAINT_ALWAYS);
        $this->processConstraintValues('always', $alwaysValues, $constraints);

        $notValues = $this->productOptionFacade
            ->getValueConstraintsForValueUsageByOperator($idValueUsage, self::CONSTRAINT_NOT);
        $this->processConstraintValues('not', $notValues, $constraints);

        return $constraints;
    }

    /**
     * @param string $operator
     * @param array $valueIds
     * @param array $constraints
     */
    protected function processConstraintValues($operator, array $valueIds, array &$constraints)
    {
        if (count($valueIds) === 0) {
            return;
        }

        foreach ($valueIds as $index => $valueId) {
            $valueIds[$index] = (int) $valueId;
        }

        $constraints[$operator] = $valueIds;
    }
}
