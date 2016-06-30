<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionExporter\Business\Model;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductOptionExporter\Dependency\Facade\ProductOptionExporterToProductInterface;
use Spryker\Zed\ProductOptionExporter\Dependency\Facade\ProductOptionExporterToProductOptionInterface;

class ExportProcessor implements ExportProcessorInterface
{

    const CONSTRAINT_ALLOW = 'ALLOW';

    const CONSTRAINT_NOT = 'NOT';

    const CONSTRAINT_ALWAYS = 'ALWAYS';

    /**
     * @var \Spryker\Zed\ProductOptionExporter\Dependency\Facade\ProductOptionExporterToProductOptionInterface
     */
    protected $productOptionFacade;

    /**
     * @var \Spryker\Zed\ProductOptionExporter\Dependency\Facade\ProductOptionExporterToProductInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductOptionExporter\Dependency\Facade\ProductOptionExporterToProductOptionInterface $productOptionFacade
     * @param \Spryker\Zed\ProductOptionExporter\Dependency\Facade\ProductOptionExporterToProductInterface $productFacade
     */
    public function __construct(
        ProductOptionExporterToProductOptionInterface $productOptionFacade,
        ProductOptionExporterToProductInterface $productFacade
    ) {
        $this->productOptionFacade = $productOptionFacade;
        $this->productFacade = $productFacade;
    }

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    public function processDataForExport(array &$resultSet, array $processedResultSet, LocaleTransfer $locale)
    {
        foreach ($resultSet as $index => $productRawData) {
            if (isset($processedResultSet[$index], $processedResultSet[$index]['product_concrete_collection'])) {
                $this->processVariants($processedResultSet[$index]['product_concrete_collection'], $locale->getIdLocale());
            }
        }

        return $processedResultSet;
    }

    /**
     * @param array $productConcreteCollection
     * @param int $idLocale
     *
     * @return array
     */
    protected function processVariants(array &$productConcreteCollection, $idLocale)
    {
        foreach ($productConcreteCollection as $index => $productConcrete) {
            $idProduct = $this->productFacade->getProductConcreteIdBySku($productConcrete['sku']);

            $productConcreteCollection[$index]['configs'] = $this->processConfigs($idProduct);
            $productConcreteCollection[$index]['options'] = $this->processOptions($productConcrete['sku'], $idProduct, $idLocale);
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
        $configPresets = $this->productOptionFacade->getConfigPresetsForProductConcrete($idProduct);
        foreach ($configPresets as $configPreset) {
            $configs[] = [
                'values' => $this->processConfigValues($configPreset['presetId']),
            ];
        }

        return $configs;
    }

    /**
     * @param int $idConfigPreset
     *
     * @return array
     */
    protected function processConfigValues($idConfigPreset)
    {
        $values = $this->productOptionFacade->getValueUsagesForConfigPreset($idConfigPreset);
        foreach ($values as $index => $value) {
            $values[$index] = (int)$value;
        }

        return $values;
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
        $typeUsages = $this->productOptionFacade->getTypeUsagesForProductConcrete($idProduct, $idLocale);

        $options = [];
        foreach ($typeUsages as $typeUsage) {
            $optionData = [
                'id' => (int)$typeUsage['idTypeUsage'],
                'label' => $typeUsage['label'],
                'isOptional' => (bool)$typeUsage['isOptional'],
                'excludes' => $this->processTypeExclusions($typeUsage['idTypeUsage']),
                'values' => $this->processValuesForTypeUsage($typeUsage['idTypeUsage'], $idLocale),
            ];
            $options[] = $optionData;
        }

        return $options;
    }

    /**
     * @param int $idProductAttributeTypeUsage
     *
     * @return array
     */
    protected function processTypeExclusions($idProductAttributeTypeUsage)
    {
        $excludes = $this->productOptionFacade->getTypeExclusionsForTypeUsage($idProductAttributeTypeUsage);
        foreach ($excludes as $index => $exclude) {
            $excludes[$index] = (int)$exclude;
        }

        return $excludes;
    }

    /**
     * @param int $idProductAttributeTypeUsage
     * @param int $idLocale
     *
     * @return array
     */
    protected function processValuesForTypeUsage($idProductAttributeTypeUsage, $idLocale)
    {
        $valueUsages = $this->productOptionFacade->getValueUsagesForTypeUsage($idProductAttributeTypeUsage, $idLocale);

        $valueData = [];
        foreach ($valueUsages as $valueUsage) {
            $valueData[] = [
                'id' => (int)$valueUsage['idValueUsage'],
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
        $this->processConstraintValues(strtolower(self::CONSTRAINT_ALLOW), $allowValues, $constraints);

        $alwaysValues = $this->productOptionFacade
            ->getValueConstraintsForValueUsageByOperator($idValueUsage, self::CONSTRAINT_ALWAYS);
        $this->processConstraintValues(strtolower(self::CONSTRAINT_ALWAYS), $alwaysValues, $constraints);

        $notValues = $this->productOptionFacade
            ->getValueConstraintsForValueUsageByOperator($idValueUsage, self::CONSTRAINT_NOT);
        $this->processConstraintValues(strtolower(self::CONSTRAINT_NOT), $notValues, $constraints);

        return $constraints;
    }

    /**
     * @param string $operator
     * @param array $valueIds
     * @param array $constraints
     *
     * @return void
     */
    protected function processConstraintValues($operator, array $valueIds, array &$constraints)
    {
        if (count($valueIds) === 0) {
            return;
        }

        foreach ($valueIds as $index => $valueId) {
            $valueIds[$index] = (int)$valueId;
        }

        sort($valueIds);

        $constraints[$operator] = $valueIds;
    }

}
