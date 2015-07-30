<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Business\Transformer;

use SprykerFeature\Zed\ProductSearch\Business\Locator\OperationLocatorInterface;
use SprykerFeature\Zed\ProductSearch\Business\Operation\OperationInterface;
use SprykerFeature\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;
use SprykerFeature\Zed\ProductSearch\Persistence\Propel\SpyProductSearchAttributesOperation;
use Propel\Runtime\Collection\ObjectCollection;

class ProductAttributesTransformer implements ProductAttributesTransformerInterface
{

    protected $fieldOperations = [];

    /**
     * @var OperationLocatorInterface
     */
    private $operationLocator;
    /**
     * @var OperationInterface
     */
    private $defaultOperation;
    /**
     * @var ProductSearchQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param ProductSearchQueryContainerInterface $queryContainer
     * @param OperationLocatorInterface $operationLocator
     * @param OperationInterface $defaultOperation
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
     * @param array  $productsRaw
     * @param array  $searchableProducts
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

                $concreteAttributes = explode('$%', $productData['concrete_attributes']);
                $concreteLocalizedAttributes = explode('$%', $productData['concrete_localized_attributes']);
                $concreteSkus = explode(',', $productData['concrete_skus']);
                $concreteNames = explode(',', $productData['concrete_names']);
                $productData['concrete_products'] = [];

                $lastSku = '';
                for ($i = 0, $l = count($concreteSkus); $i < $l; $i++) {
                    if ($lastSku === $concreteSkus[$i]) {
                        continue;
                    }
                    $encodedAttributes = json_decode($concreteAttributes[$i], true);
                    $encodedLocalizedAttributes = json_decode($concreteLocalizedAttributes[$i], true);
                    if (is_null($encodedLocalizedAttributes)) {
                        $encodedLocalizedAttributes = [];
                    }
                    $mergedAttributes = array_merge($encodedAttributes, $encodedLocalizedAttributes);

                    $lastSku = $concreteSkus[$i];
                    $productData['concrete_products'][] = [
                        'sku' => $concreteSkus[$i],
                        'attributes' => $mergedAttributes,
                        'name' => $concreteNames[$i],
                    ];
                }

                $attributes = $this->mapProductAttributes($abstractAttributes);
                $searchableProducts[$index] = array_merge_recursive($searchableProducts[$index], $attributes);
            }
        }

        return array_filter($searchableProducts);
    }

    /**
     * @param array  $attributes
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
        return (!empty($this->fieldOperations));
    }

    protected function initFieldToOperationMapping()
    {
        foreach ($this->getFieldOperations() as $fieldOperation) {
            $operationName = $fieldOperation->getOperation();

            if (!is_null($this->operationLocator->findOperationByName($operationName))) {
                $fieldName = $fieldOperation->getSpyProductAttributesMetadata()->getkey();
                $this->fieldOperations[$fieldName][$operationName][] = $fieldOperation->getTargetField();
            } else {
                throw new \RuntimeException(
                    sprintf(
                        'No operation with name %s found to map field %s',
                        $operationName,
                        $fieldOperation->getSpyProductAttributesMetadata()->getkey()
                    )
                );
            }
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
     * @return SpyProductSearchAttributesOperation[]|ObjectCollection
     */
    protected function getFieldOperations()
    {
        return $this->queryContainer->queryFieldOperations()->find();
    }

}
