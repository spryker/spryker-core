<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business;

use Spryker\Zed\DiscountPromotion\Business\Cart\CartValidator;
use Spryker\Zed\DiscountPromotion\Business\Cart\CartValidatorInterface;
use Spryker\Zed\DiscountPromotion\Business\Checker\DiscountPromotionItemChecker;
use Spryker\Zed\DiscountPromotion\Business\Checker\DiscountPromotionItemCheckerInterface;
use Spryker\Zed\DiscountPromotion\Business\Checker\DiscountPromotionVoucherCodeApplicationChecker;
use Spryker\Zed\DiscountPromotion\Business\Checker\DiscountPromotionVoucherCodeApplicationCheckerInterface;
use Spryker\Zed\DiscountPromotion\Business\CollectorStrategy\DiscountPromotionCollectorStrategyComposite;
use Spryker\Zed\DiscountPromotion\Business\CollectorStrategy\DiscountPromotionCollectorStrategyCompositeInterface;
use Spryker\Zed\DiscountPromotion\Business\CollectorStrategy\DiscountPromotionCollectorStrategyInterface;
use Spryker\Zed\DiscountPromotion\Business\CollectorStrategy\MultipleSkusDiscountPromotionCollectorStrategy;
use Spryker\Zed\DiscountPromotion\Business\CollectorStrategy\SingleSkuDiscountPromotionCollectorStrategy;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator\DiscountableItemCreator;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator\DiscountableItemCreatorInterface;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator\DiscountPromotionCreator;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator\DiscountPromotionCreatorInterface;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionUpdater\DiscountPromotionUpdater;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionUpdater\DiscountPromotionUpdaterInterface;
use Spryker\Zed\DiscountPromotion\Business\Expander\DiscountPromotionQuoteExpander;
use Spryker\Zed\DiscountPromotion\Business\Expander\DiscountPromotionQuoteExpanderInterface;
use Spryker\Zed\DiscountPromotion\Business\Filter\DiscountPromotionItemFilter;
use Spryker\Zed\DiscountPromotion\Business\Filter\DiscountPromotionItemFilterInterface;
use Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy\PromotionAvailabilityCalculator;
use Spryker\Zed\DiscountPromotion\Business\Model\DiscountPromotionReader;
use Spryker\Zed\DiscountPromotion\Business\Writer\DiscountVoucherQuoteWriter;
use Spryker\Zed\DiscountPromotion\Business\Writer\DiscountVoucherQuoteWriterInterface;
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
     * @return \Spryker\Zed\DiscountPromotion\Business\CollectorStrategy\DiscountPromotionCollectorStrategyCompositeInterface
     */
    public function createDiscountPromotionCollectorStrategyComposite(): DiscountPromotionCollectorStrategyCompositeInterface
    {
        return new DiscountPromotionCollectorStrategyComposite(
            $this->getDiscountPromotionCollectorStrategies(),
            $this->getRepository(),
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
        return new DiscountPromotionReader($this->getRepository());
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

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator\DiscountableItemCreatorInterface
     */
    public function createDiscountableItemCreator(): DiscountableItemCreatorInterface
    {
        return new DiscountableItemCreator(
            $this->createPromotionAvailabilityCalculator(),
            $this->createDiscountPromotionItemChecker(),
            $this->createDiscountPromotionQuoteExpander(),
            $this->createDiscountVoucherQuoteWriter(),
        );
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\Checker\DiscountPromotionItemCheckerInterface
     */
    public function createDiscountPromotionItemChecker(): DiscountPromotionItemCheckerInterface
    {
        return new DiscountPromotionItemChecker();
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\Expander\DiscountPromotionQuoteExpanderInterface
     */
    public function createDiscountPromotionQuoteExpander(): DiscountPromotionQuoteExpanderInterface
    {
        return new DiscountPromotionQuoteExpander(
            $this->getProductFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\Writer\DiscountVoucherQuoteWriterInterface
     */
    public function createDiscountVoucherQuoteWriter(): DiscountVoucherQuoteWriterInterface
    {
        return new DiscountVoucherQuoteWriter();
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\CollectorStrategy\DiscountPromotionCollectorStrategyInterface
     */
    public function createMultipleSkusDiscountPromotionCollectorStrategy(): DiscountPromotionCollectorStrategyInterface
    {
        return new MultipleSkusDiscountPromotionCollectorStrategy(
            $this->createDiscountableItemCreator(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\CollectorStrategy\DiscountPromotionCollectorStrategyInterface
     */
    public function createSingleSkuDiscountPromotionCollectorStrategy(): DiscountPromotionCollectorStrategyInterface
    {
        return new SingleSkuDiscountPromotionCollectorStrategy(
            $this->createDiscountableItemCreator(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\Checker\DiscountPromotionVoucherCodeApplicationCheckerInterface
     */
    public function createDiscountPromotionVoucherCodeApplicationChecker(): DiscountPromotionVoucherCodeApplicationCheckerInterface
    {
        return new DiscountPromotionVoucherCodeApplicationChecker();
    }

    /**
     * @return array<\Spryker\Zed\DiscountPromotion\Business\CollectorStrategy\DiscountPromotionCollectorStrategyInterface>
     */
    public function getDiscountPromotionCollectorStrategies(): array
    {
        return [
            $this->createMultipleSkusDiscountPromotionCollectorStrategy(),
            $this->createSingleSkuDiscountPromotionCollectorStrategy(),
        ];
    }
}
