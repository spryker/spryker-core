<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Importer\Builder;

use Generated\Shared\Transfer\AbstractProductTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use SprykerFeature\Zed\Product\Business\Builder\ProductBuilderInterface;

class ProductBuilder implements ProductBuilderInterface
{

    const ABSTRACT_PRODUCT_REFERENCE = 'refSku';
    const BUNDLED_PRODUCTS_REFERENCES = 'refBundleSkus';
    const PRODUCT_URL_FIELD = 'url';
    const PRODUCT_NAME_FIELD = 'name';
    const PRODUCT_SKU_FIELD = 'sku';
    const LIST_SEPARATOR = '|';

    /**
     * @param array $data
     *
     * @return AbstractProductTransfer|ConcreteProductTransfer
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

        return (false !== array_search($name, $properties));
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
     * @return AbstractProductTransfer|ConcreteProductTransfer
     */
    protected function generateTypedProduct(array &$data)
    {
        if (empty($data[self::ABSTRACT_PRODUCT_REFERENCE])) {
            $product = new AbstractProductTransfer();
        } else {
            $product = new ConcreteProductTransfer();
            $product->setAbstractProductSku($data[self::ABSTRACT_PRODUCT_REFERENCE]);
        }

        unset($data[self::ABSTRACT_PRODUCT_REFERENCE]);
        unset($data[self::BUNDLED_PRODUCTS_REFERENCES]); //@todo handle bundle creation

        return $product;
    }

}
