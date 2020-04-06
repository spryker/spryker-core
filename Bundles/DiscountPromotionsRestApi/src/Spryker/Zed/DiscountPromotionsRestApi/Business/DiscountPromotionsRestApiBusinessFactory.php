<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotionsRestApi\Business;

use Spryker\Zed\DiscountPromotionsRestApi\Business\Mapper\DiscountPromotionMapper;
use Spryker\Zed\DiscountPromotionsRestApi\Business\Mapper\DiscountPromotionMapperInterface;
use Spryker\Zed\DiscountPromotionsRestApi\Dependency\Facade\DiscountPromotionsRestApiToDiscountPromotionFacadeInterface;
use Spryker\Zed\DiscountPromotionsRestApi\DiscountPromotionsRestApiDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DiscountPromotionsRestApi\DiscountPromotionsRestApiConfig getConfig()
 */
class DiscountPromotionsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\DiscountPromotionsRestApi\Business\Mapper\DiscountPromotionMapperInterface
     */
    public function createDiscountPromotionMapper(): DiscountPromotionMapperInterface
    {
        return new DiscountPromotionMapper($this->getDiscountPromotionFacade());
    }

    /**
     * @return \Spryker\Zed\DiscountPromotionsRestApi\Dependency\Facade\DiscountPromotionsRestApiToDiscountPromotionFacadeInterface
     */
    protected function getDiscountPromotionFacade(): DiscountPromotionsRestApiToDiscountPromotionFacadeInterface
    {
        return $this->getProvidedDependency(DiscountPromotionsRestApiDependencyProvider::FACADE_DISCOUNT_PROMOTION);
    }
}
