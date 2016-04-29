<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Transformer;

use Spryker\Zed\ProductSearch\Business\Locator\OperationLocatorInterface;
use Spryker\Zed\ProductSearch\Business\Operation\OperationInterface;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;

// TODO: refactor/remove
class ProductAttributesTransformer implements ProductAttributesTransformerInterface
{

    /**
     * @var array
     */
    protected $fieldOperations = [];

    /**
     * @var \Spryker\Zed\ProductSearch\Business\Locator\OperationLocatorInterface
     */
    private $operationLocator;

    /**
     * @var \Spryker\Zed\ProductSearch\Business\Operation\OperationInterface
     */
    private $defaultOperation;

    /**
     * @var \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductSearch\Business\Locator\OperationLocatorInterface $operationLocator
     * @param \Spryker\Zed\ProductSearch\Business\Operation\OperationInterface $defaultOperation
     */
    public function __construct(
        ProductSearchQueryContainerInterface $queryContainer,
        OperationLocatorInterface $operationLocator,
        OperationInterface $defaultOperation
    ) {
        $this->operationLocator = $operationLocator;
        $this->defaultOperation = $defaultOperation;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param array $productsRaw
     * @param array $searchableProducts
     *
     * @return array
     */
    public function buildProductAttributes(array $productsRaw, array $searchableProducts)
    {
        if (!$this->isInitialized()) {
            $this->initFieldToOperationMapping();
        }

        foreach ($productsRaw as $index => $productData) {
            if (isset($searchableProducts[$index])) {
                $productUrls = explode(', ', $productData['product_urls']);
                $productData['url'] = $productUrls[0];

                $abstractAttributes = json_decode($productData['abstract_attributes'], true);
                $abstractLocalizedAttributes = json_decode($productData['abstract_localized_attributes'], true);
                $abstractAttributes = array_merge($abstractAttributes, $abstractLocalizedAttributes);

//                $concreteAttributes = json_decode('[' . $productData['concrete_attributes'] . ']', true);
//                $concreteLocalizedAttributes = json_decode('[' . $productData['concrete_localized_attributes'] . ']', true);
//
//                $concreteSkus = explode(',', $productData['concrete_skus']);
//                $concreteNames = explode(',', $productData['concrete_names']);
//                $productData['product_concrete_collection'] = [];
//
//                $lastSku = '';
//                for ($i = 0, $l = count($concreteSkus); $i < $l; $i++) {
//                    if ($lastSku === $concreteSkus[$i]) {
//                        continue;
//                    }
//                    $encodedAttributes = $concreteAttributes[$i];
//                    $encodedLocalizedAttributes = $concreteLocalizedAttributes[$i];
//                    if ($encodedAttributes === null) {
//                        $encodedAttributes = [];
//                    }
//                    if ($encodedLocalizedAttributes === null) {
//                        $encodedLocalizedAttributes = [];
//                    }
//                    $mergedAttributes = array_merge($encodedAttributes, $encodedLocalizedAttributes);
//
//                    $lastSku = $concreteSkus[$i];
//                    $productData['product_concrete_collection'][] = [
//                        'sku' => $concreteSkus[$i],
//                        'attributes' => $mergedAttributes,
//                        'name' => $concreteNames[$i],
//                    ];
//                }

                $attributes = $this->mapProductAttributes($abstractAttributes);
                $searchableProducts[$index] = array_merge_recursive($searchableProducts[$index], $attributes);
            }
        }

        return array_filter($searchableProducts);
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function mapProductAttributes(array $attributes)
    {
        $document = [];

        foreach ($attributes as $field => $value) {
            if (array_key_exists($field, $this->fieldOperations)) {
                $document = $this->executeSpecificOperation($attributes, $document, $field);
            } else {
                $document = $this->defaultOperation->enrichDocument($attributes, $document, $field, null);
            }
        }

        return $document;
    }

    /**
     * @param array $attributes
     * @param array $document
     * @param string $fieldName
     *
     * @return array
     */
    protected function executeSpecificOperation(array $attributes, array $document, $fieldName)
    {
        foreach ($this->fieldOperations[$fieldName] as $operation => $targetFields) {
            $operationCommand = $this->operationLocator->findOperationByName($operation);

            foreach ($targetFields as $targetField) {
                $document = $operationCommand->enrichDocument($attributes, $document, $fieldName, $targetField);
            }
        }

        return $document;
    }

    /**
     * @return bool
     */
    protected function isInitialized()
    {
        return (bool)$this->fieldOperations;
    }

    /**
     * @return void
     */
    protected function initFieldToOperationMapping()
    {
        foreach ($this->getFieldOperations() as $fieldOperation) {
            $operationName = $fieldOperation->getOperation();

            if ($this->operationLocator->findOperationByName($operationName) === null) {
                throw new \RuntimeException(
                    sprintf(
                        'No operation with name %s found to map field %s',
                        $operationName,
                        $fieldOperation->getSpyProductAttributesMetadata()->getkey()
                    )
                );
            }

            $fieldName = $fieldOperation->getSpyProductAttributesMetadata()->getkey();
            $this->fieldOperations[$fieldName][$operationName][] = $fieldOperation->getTargetField();
        }
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function formatAttributes(array $attributes)
    {
        $newKeys = array_map(function ($name) {
            return str_replace(' ', '', lcfirst(ucwords($name)));
        }, array_keys($attributes));

        return array_combine($newKeys, $attributes);
    }

    /**
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributesOperation[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getFieldOperations()
    {
        return $this->queryContainer->queryFieldOperations()->find();
    }

}
