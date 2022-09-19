<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Expander;

use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Spryker\Zed\DiscountPromotion\Communication\Form\DiscountPromotionFormType;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToLocaleFacadeInterface;
use Symfony\Component\Form\FormBuilderInterface;

class DiscountPromotionFormExpander implements DiscountPromotionFormExpanderInterface
{
    /**
     * @var \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToLocaleFacadeInterface
     */
    protected DiscountPromotionToLocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToLocaleFacadeInterface $localeFacade
     */
    public function __construct(DiscountPromotionToLocaleFacadeInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expandFormType(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        return $builder->get(DiscountConfiguratorTransfer::DISCOUNT_CALCULATOR)
            ->add(
                DiscountCalculatorTransfer::DISCOUNT_PROMOTION,
                DiscountPromotionFormType::class,
                [
                    'data_class' => DiscountPromotionTransfer::class,
                    'label' => false,
                    'locale' => $options[DiscountPromotionFormType::OPTION_LOCALE] ?? $this->localeFacade->getCurrentLocaleName(),
                ],
            );
    }
}
