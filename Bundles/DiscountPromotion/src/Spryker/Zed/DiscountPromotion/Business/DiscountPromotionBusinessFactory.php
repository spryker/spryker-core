<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business;

use Spryker\Zed\DiscountPromotion\Business\DiscountCollectorStrategy\DiscountPromotionCollectorStrategy;
use Spryker\Zed\DiscountPromotion\Business\Model\DiscountPromotionReader;
use Spryker\Zed\DiscountPromotion\Business\Model\DiscountPromotionWriter;
use Spryker\Zed\DiscountPromotion\DiscountPromotionDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainer getQueryContainer()
 */
class DiscountPromotionBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\DiscountCollectorStrategy\DiscountPromotionCollectorStrategyInterface
     */
    public function createDiscountPromotionCollectorStrategy()
    {
         return new DiscountPromotionCollectorStrategy(
             $this->getProductFacade(),
             $this->getQueryContainer()
         );
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\Model\DiscountPromotionWriterInterface
     */
    public function createDiscountPromotionWriter()
    {
        return new DiscountPromotionWriter();
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\Model\DiscountPromotionReaderInterface
     */
    public function createDiscountPromotionReader()
    {
        return new DiscountPromotionReader();
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(DiscountPromotionDependencyProvider::FACADE_PRODUCT);
    }

}
