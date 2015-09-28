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

                //TODO work in progress: https://kartenmacherei.atlassian.net/browse/KSP-868
                $categoryParentIds = explode(',', $productData['category_parent_ids']); //16,104,104,104,104,2,2,2,16,16,16,2,8,8,8,8
                $productOrders = explode(',', $productData['product_order']); //70,0,0,0,0,70,70,70,70,70,70,70,0,0,0,0
                $nodeIdList = explode(',', $productData['node_id']); //2,16

                $productOrderList = [];
                for ($x=0; $x<count($productOrders); $x++) {
                    $parent = $categoryParentIds[$x];
                    $order = $productOrders[$x];
                    $productOrderList[$parent] = $order;
                }

                $productUrls = explode(', ', $productData['product_urls']);
                $productData['url'] = $productUrls[0];

                $abstractAttributes = json_decode($productData['abstract_attributes'], true);
                $abstractLocalizedAttributes = json_decode($productData['abstract_localized_attributes'], true);
                $abstractAttributes = array_merge($abstractAttributes, $abstractLocalizedAttributes);

                $concreteAttributes = json_decode('[' . $productData['concrete_attributes'] . ']', true);
                $concreteLocalizedAttributes = json_decode('[' . $productData['concrete_localized_attributes'] . ']', true);

                $concreteSkus = explode(',', $productData['concrete_skus']);
                $concreteNames = explode(',', $productData['concrete_names']);
                $productData['concrete_products'] = [];

                foreach ($nodeIdList as $nodeId) {
                    /*$s = [
                        'facet-value' => $productOrderList[$nodeId],
                        'facet-name' => 'product_order',
                        'facet-key' => $nodeId,
                    ];
                    $abstractAttributes['product_order'][] = $s;*/
                    $abstractAttributes['product_order_'.$nodeId] = $productOrderList[$nodeId];
                }

                $lastSku = '';
                for ($i = 0, $l = count($concreteSkus); $i < $l; $i++) {
                    if ($lastSku === $concreteSkus[$i]) {
                        continue;
                    }
                    $encodedAttributes = $concreteAttributes[$i];
                    $encodedLocalizedAttributes = $concreteLocalizedAttributes[$i];
                    if (null === $encodedAttributes) {
                        $encodedAttributes = [];
                    }
                    if (null === $encodedLocalizedAttributes) {
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

                //TODO work in progress: https://kartenmacherei.atlassian.net/browse/KSP-868
                //$abstractAttributes['product_order_1']= rand(1,10); //request in Yves, comes from DB xxx
                //$abstractAttributes['product_order_2']= rand(1,10); //request in Yves, comes from DB xxx
                //$abstractAttributes['product_order_3']= rand(1,10); //request in Yves, comes from DB xxx, _3 is category id
                $attributes = $this->mapProductAttributes($abstractAttributes);
                //$attributes['integer-sort']['product'] = rand(1,10); //request in Yves

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
