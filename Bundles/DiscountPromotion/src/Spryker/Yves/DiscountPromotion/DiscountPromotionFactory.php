<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DiscountPromotion;

use Spryker\Yves\DiscountPromotion\Mapper\PromotionProductMapper;
use Spryker\Yves\Kernel\AbstractFactory;

class DiscountPromotionFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Yves\DiscountPromotion\Mapper\PromotionProductMapperInterface
     */
    public function createPromotionProductMapper()
    {
        return new PromotionProductMapper($this->getProductClient(), $this->getProductMapperPlugin());
    }

    /**
     * @return \Spryker\Yves\DiscountPromotion\Dependency\Client\DiscountPromotionToProductInterface
     */
    protected function getProductClient()
    {
        return $this->getProvidedDependency(DiscountPromotionDependencyProvider::PRODUCT_CLIENT);
    }

    /**
     * @return \Spryker\Yves\DiscountPromotion\Dependency\StorageProductMapperPluginInterface
     */
    protected function getProductMapperPlugin()
    {
        return $this->getProvidedDependency(DiscountPromotionDependencyProvider::PRODUCT_MAPPER_PLUGIN);
    }

}
