<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint;

use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface;
use Symfony\Component\Validator\Constraint;

class ProductAbstractAttributeUniqueCombinationConstraint extends Constraint
{
    /**
     * @var string
     */
    protected const PARAMETER_ATTRIBUTE = '%attribute%';

    /**
     * @var string
     */
    protected string $message = 'The attribute %attribute% already exists. Please define another one';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface
     */
    protected ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface
     */
    protected ProductMerchantPortalGuiToProductFacadeInterface $productFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade,
        ProductMerchantPortalGuiToProductFacadeInterface $productFacade,
        ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
    ) {
        parent::__construct();

        $this->productAttributeFacade = $productAttributeFacade;
        $this->productFacade = $productFacade;
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface
     */
    public function getProductAttributeFacade(): ProductMerchantPortalGuiToProductAttributeFacadeInterface
    {
        return $this->productAttributeFacade;
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface
     */
    public function getProductFacade(): ProductMerchantPortalGuiToProductFacadeInterface
    {
        return $this->productFacade;
    }

    /**
     * @param string $attribute
     *
     * @return string
     */
    public function getMessage(string $attribute): string
    {
        /** @phpstan-var array<string, string> $parameters */
        $parameters = [
            static::PARAMETER_ATTRIBUTE => $attribute,
        ];

        return $this->translatorFacade->trans($this->message, $parameters);
    }

    /**
     * @return array<string>|string
     */
    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }
}
