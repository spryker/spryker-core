<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form\Constraint;

use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueEmail extends SymfonyConstraint
{
    public const OPTION_MERCHANT_FACADE = 'merchantFacade';
    public const OPTION_CURRENT_ID_MERCHANT = 'currentIdMerchant';

    /**
     * @var \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var int|null
     */
    protected $currentIdMerchant;

    /**
     * @return \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantGuiToMerchantFacadeInterface
    {
        return $this->merchantFacade;
    }

    /**
     * @return int|null
     */
    public function getCurrentIdMerchant(): ?int
    {
        return $this->currentIdMerchant;
    }

    /**
     * @return string
     */
    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }
}
