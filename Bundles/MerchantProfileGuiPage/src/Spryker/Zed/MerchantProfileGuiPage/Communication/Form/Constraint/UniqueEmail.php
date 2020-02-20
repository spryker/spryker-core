<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint;

use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToMerchantFacadeInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueEmail extends SymfonyConstraint
{
    public const OPTION_MERCHANT_FACADE = 'merchantFacade';
    public const OPTION_CURRENT_ID_MERCHANT = 'currentIdMerchant';

    /**
     * @var \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var int|null
     */
    protected $currentIdMerchant;

    /**
     * @return \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantProfileGuiPageToMerchantFacadeInterface
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
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }
}
