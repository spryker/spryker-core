<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication;

use Spryker\Zed\DiscountPromotion\Communication\Expander\DiscountPromotionFormExpander;
use Spryker\Zed\DiscountPromotion\Communication\Expander\DiscountPromotionFormExpanderInterface;
use Spryker\Zed\DiscountPromotion\Communication\Form\Constraint\AbstractSkusExistConstraint;
use Spryker\Zed\DiscountPromotion\Communication\Form\DiscountPromotionFormType;
use Spryker\Zed\DiscountPromotion\Communication\Form\Transformer\AbstractSkusTransformer;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToLocaleFacadeInterface;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductInterface;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToTranslatorFacadeInterface;
use Spryker\Zed\DiscountPromotion\DiscountPromotionDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface getFacade()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface getRepository()
 */
class DiscountPromotionCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    public function createDiscountFormPromotionType()
    {
        return DiscountPromotionFormType::class;
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface<array<string>|null, string>
     */
    public function createAbstractSkusTransformer(): DataTransformerInterface
    {
        return new AbstractSkusTransformer();
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createAbstractSkusExistConstraint(): Constraint
    {
        return new AbstractSkusExistConstraint([
            AbstractSkusExistConstraint::OPTION_PRODUCT_FACADE => $this->getProductFacade(),
            AbstractSkusExistConstraint::OPTION_TRANSLATOR_FACADE => $this->getTranslatorFacade(),
        ]);
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Communication\Expander\DiscountPromotionFormExpanderInterface
     */
    public function createDiscountPromotionFormExpander(): DiscountPromotionFormExpanderInterface
    {
        return new DiscountPromotionFormExpander($this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductInterface
     */
    public function getProductFacade(): DiscountPromotionToProductInterface
    {
        return $this->getProvidedDependency(DiscountPromotionDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): DiscountPromotionToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(DiscountPromotionDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToLocaleFacadeInterface
     */
    public function getLocaleFacade(): DiscountPromotionToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(DiscountPromotionDependencyProvider::FACADE_LOCALE);
    }
}
