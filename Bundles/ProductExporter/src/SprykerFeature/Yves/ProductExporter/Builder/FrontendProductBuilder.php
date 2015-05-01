<?php

namespace SprykerFeature\Yves\ProductExporter\Builder;

use Generated\Yves\Ide\FactoryAutoCompletion\ProductExporter;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerFeature\Yves\ProductExporter\Model\Product;

/**
 * Class FrontendProductBuilder
 * @package SprykerFeature\Yves\Product\Builder
 */
class FrontendProductBuilder implements FrontendProductBuilderInterface
{
    /**
     * @var FactoryInterface|ProductExporter
     */
    protected $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array $productData
     *
     * @return Product
     */
    public function buildProduct(array $productData)
    {
        $product = $this->factory->createModelProduct();

        foreach ($productData as $name => $value) {
            $setter = 'set' . ucfirst(strtolower($name));

            if (method_exists($product, $setter)) {
                $product->{$setter}($value);
            } else {
                $product->addAttribute($name, $value);
            }
        }

        return $product;
    }
}
