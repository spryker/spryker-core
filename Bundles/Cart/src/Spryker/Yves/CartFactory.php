<?php


namespace Spryker\Yves\Cart;


use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Cart\Mapper\AttributeMapper;

class CartFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Availability\AvailabilityClientInterface
     */
    public function getAvailabilityClient()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CLIENT_AVAILABILITY);
    }

    /**
     * @return \Spryker\Client\ProductOption\ProductOptionClientInterface
     */
    public function getProductOptionClient()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CLIENT_PRODUCT_OPTION);
    }

    public function createAttributeMapper()
    {
        return new AttributeMapper(
            $this->getProductOptionClient(),
            $this->getAvailabilityClient()
        );
    }

}
