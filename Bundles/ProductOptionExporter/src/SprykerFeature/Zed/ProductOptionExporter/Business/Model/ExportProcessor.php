<?php

namespace SprykerFeature\Zed\ProductOptionExporter\Business\Model;

use SprykerFeature\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use SprykerFeature\Zed\ProductOptionExporter\Dependency\Facade\ProductOptionExporterToProductInterface;
use SprykerFeature\Zed\ProductOptionExporter\Dependency\Facade\ProductOptionExporterToLocaleInterface;

/**
 * (c) Spryker Systems GmbH copyright protected
 */
class ExportProcessor implements ExportProcessorInterface
{

    const CONSTRAINT_ALLOW = 'ALLOW';

    const CONSTRAINT_NOT = 'NOT';

    const CONSTRAINT_ALWAYS = 'ALWAYS';

    /**
     * @var ProductOptionQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var ProductOptionExporterToProductInterface
     */
    protected $productFacade;

    /**
     * @var ProductOptionExporterToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param ProductOptionQueryContainerInterface $queryContainer
     */
    public function __construct(
        ProductOptionQueryContainerInterface $queryContainer,
        ProductOptionExporterToProductInterface $productFacade,
        ProductOptionExporterToLocaleInterface $localeFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->productFacade = $productFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet)
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
            $concreteProducts[$index]['options'] = $this->processOptions($idProduct, $idLocale);
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
        $configPresets = $this->queryContainer->queryConfigPresetsForConcreteProduct($idProduct);
        foreach ($configPresets as $configPreset) {
            $values = $this->queryContainer->queryValueUsagesForConfigPreset($configPreset['presetId']);
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
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return array
     */
    protected function processOptions($idProduct, $idLocale)
    {
        $typeUsages = $this->queryContainer->queryTypeUsagesForConcreteProduct($idProduct, $idLocale);

        $options = [];
        foreach ($typeUsages as $typeUsage) {
            $optionData = [
                'id' => (int) $typeUsage['idTypeUsage'],
                'label' => $typeUsage['label'],
                'isOptional' => (bool) $typeUsage['isOptional'],
                'taxRate' => $this->processOptionTaxRate($idProduct, $typeUsage['idTypeUsage']),
                'excludes' => $this->processTypeExclusions($typeUsage['idTypeUsage']),
                'values' => $this->processValuesForTypeUsage($typeUsage['idTypeUsage'], $idLocale)
            ];
            $options[] = $optionData;
        }

        return $options;
    }

    protected function processTypeExclusions($idTypeUsage)
    {
        $excludes = $this->queryContainer->queryTypeExclusionsForTypeUsage($idTypeUsage);
        foreach ($excludes as $index => $exclude) {
            $excludes[$index] = (int) $exclude;
        }

        return $excludes;
    }

    /**
     * @param int $idProduct
     * @param int $idTypeUsage
     *
     * @return null|string
     */
    protected function processOptionTaxRate($idProduct, $idTypeUsage)
    {
        $typeUsageTaxRate = $this->queryContainer
            ->queryEffectiveTaxRateForTypeUsage($idTypeUsage);

        if (null === $typeUsageTaxRate) {
            $typeUsageTaxRate = $this->queryContainer
                ->queryEffectiveTaxRateForAbstractProduct($idProduct);
        }

        return $typeUsageTaxRate;
    }

    /**
     * @param int $idTypeUsage
     * @param int $idLocale
     *
     * @return array
     */
    protected function processValuesForTypeUsage($idTypeUsage, $idLocale)
    {
        $valueUsages = $this->queryContainer->queryValueUsagesForTypeUsage($idTypeUsage, $idLocale);

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

        $allowValues = $this->queryContainer
            ->queryValueConstraintsForValueUsageByOperator($idValueUsage, self::CONSTRAINT_ALLOW);
        $this->processConstraintValues('allow', $allowValues, $constraints);

        $alwaysValues = $this->queryContainer
            ->queryValueConstraintsForValueUsageByOperator($idValueUsage, self::CONSTRAINT_ALWAYS);
        $this->processConstraintValues('always', $alwaysValues, $constraints);

        $notValues = $this->queryContainer
            ->queryValueConstraintsForValueUsageByOperator($idValueUsage, self::CONSTRAINT_NOT);
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
