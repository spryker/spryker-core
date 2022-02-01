<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Form\Constraint;

use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductInterface;
use Symfony\Component\Validator\Constraint;

class AbstractSkusExistConstraint extends Constraint
{
    /**
     * @var string
     */
    public const OPTION_PRODUCT_FACADE = 'productFacade';

    /**
     * @var string
     */
    public const OPTION_TRANSLATOR_FACADE = 'translatorFacade';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'Unknown SKU(s): %non_existing_skus%';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_NON_EXISTING_SKUS = '%non_existing_skus%';

    /**
     * @var \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @return \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductInterface
     */
    public function getProductFacade(): DiscountPromotionToProductInterface
    {
        return $this->productFacade;
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }

    /**
     * @param string $skus
     *
     * @return string
     */
    public function getMessage(string $skus): string
    {
        return $this->translatorFacade->trans(
            static::ERROR_MESSAGE,
            [
                static::ERROR_MESSAGE_PARAMETER_NON_EXISTING_SKUS => $skus,
            ],
        );
    }
}
