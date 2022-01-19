<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business;

use Spryker\Zed\DiscountPromotion\Business\Cart\CartValidator;
use Spryker\Zed\DiscountPromotion\Business\Cart\CartValidatorInterface;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator\DiscountPromotionCreator;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator\DiscountPromotionCreatorInterface;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionUpdater\DiscountPromotionUpdater;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionUpdater\DiscountPromotionUpdaterInterface;
use Spryker\Zed\DiscountPromotion\Business\Filter\DiscountPromotionItemFilter;
use Spryker\Zed\DiscountPromotion\Business\Filter\DiscountPromotionItemFilterInterface;
use Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy\DiscountPromotionCollectorStrategy;
use Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy\PromotionAvailabilityCalculator;
use Spryker\Zed\DiscountPromotion\Business\Model\DiscountPromotionReader;
use Spryker\Zed\DiscountPromotion\Business\Model\Mapper\DiscountPromotionMapper;
use Spryker\Zed\DiscountPromotion\DiscountPromotionDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface getRepository()
 */
class DiscountPromotionBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy\DiscountPromotionCollectorStrategyInterface
     */
    public function createDiscountPromotionCollectorStrategy()
    {
        return new DiscountPromotionCollectorStrategy(
            $this->getProductFacade(),
            $this->getQueryContainer(),
            $this->createPromotionAvailabilityCalculator(),
        );
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy\PromotionAvailabilityCalculatorInterface
     */
    protected function createPromotionAvailabilityCalculator()
    {
        return new PromotionAvailabilityCalculator($this->getAvailabilityFacade());
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\Model\DiscountPromotionReaderInterface
     */
    public function createDiscountPromotionReader()
    {
        return new DiscountPromotionReader($this->getQueryContainer(), $this->createDiscountPromotionMapper());
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\Model\Mapper\DiscountPromotionMapperInterface
     */
    protected function createDiscountPromotionMapper()
    {
        return new DiscountPromotionMapper();
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\Filter\DiscountPromotionItemFilterInterface
     */
    public function createDiscountPromotionItemFilter(): DiscountPromotionItemFilterInterface
    {
        return new DiscountPromotionItemFilter();
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(DiscountPromotionDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToAvailabilityInterface
     */
    protected function getAvailabilityFacade()
    {
        return $this->getProvidedDependency(DiscountPromotionDependencyProvider::FACADE_AVAILABILITY);
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator\DiscountPromotionCreatorInterface
     */
    public function createDiscountPromotionCreator(): DiscountPromotionCreatorInterface
    {
        return new DiscountPromotionCreator($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionUpdater\DiscountPromotionUpdaterInterface
     */
    public function createDiscountPromotionUpdater(): DiscountPromotionUpdaterInterface
    {
        return new DiscountPromotionUpdater($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\Cart\CartValidatorInterface
     */
    public function createCartValidator(): CartValidatorInterface
    {
        return new CartValidator();
    }
}
