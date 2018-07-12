<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class SkuExists extends SymfonyConstraint
{
    public const OPTION_PRODUCT_FACADE = 'productFacade';

    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToProductFacadeInterface
     */
    public function getProductFacade()
    {
        return $this->productFacade;
    }

    /**
     * @return string
     */
    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }
}
