<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Importer\Builder;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Builder\ProductBuilderInterface;

class ProductBuilder implements ProductBuilderInterface
{

    const PRODUCT_ABSTRACT_REFERENCE = 'refSku';
    const BUNDLED_PRODUCTS_REFERENCES = 'refBundleSkus';
    const PRODUCT_URL_FIELD = 'url';
    const PRODUCT_NAME_FIELD = 'name';
    const PRODUCT_SKU_FIELD = 'sku';
    const LIST_SEPARATOR = '|';

    /**
     * @param array $data
     *
     * @return ProductAbstractTransfer|ProductConcreteTransfer
     */
    public function buildProduct(array $data)
    {
        $product = $this->generateTypedProduct($data);
        $attributes = [];

        foreach ($data as $key => $value) {
            if ($this->isProperty($key)) {
                $setter = $this->getSetterName($key);

                if (method_exists($product, $setter)) {
                    $product->{$setter}($value);
                }
            } else {
                if (is_string($value) && strpos($value, self::LIST_SEPARATOR) !== false) {
                    $value = explode(self::LIST_SEPARATOR, $value);
                }

                $attributes[$key] = $value;
            }
        }
        $product->setAttributes($attributes);

        return $product;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function isProperty($name)
    {
        $properties = [self::PRODUCT_URL_FIELD, self::PRODUCT_NAME_FIELD, self::PRODUCT_SKU_FIELD];

        return (array_search($name, $properties) !== false);
    }

    /**
     * @param string $property
     *
     * @return string
     */
    protected function getSetterName($property)
    {
        return 'set' . ucfirst($property);
    }

    /**
     * @param array $data
     *
     * @return ProductAbstractTransfer|ProductConcreteTransfer
     */
    protected function generateTypedProduct(array &$data)
    {
        if (empty($data[self::PRODUCT_ABSTRACT_REFERENCE])) {
            $product = new ProductAbstractTransfer();
        } else {
            $product = new ProductConcreteTransfer();
            $product->setProductAbstractSku($data[self::PRODUCT_ABSTRACT_REFERENCE]);
        }

        unset($data[self::PRODUCT_ABSTRACT_REFERENCE]);
        unset($data[self::BUNDLED_PRODUCTS_REFERENCES]); //@todo handle bundle creation

        return $product;
    }

}
