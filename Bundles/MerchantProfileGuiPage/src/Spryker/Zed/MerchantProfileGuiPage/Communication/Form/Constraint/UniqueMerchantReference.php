<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;

class UniqueMerchantReference extends Constraint
{
    public const OPTION_CURRENT_MERCHANT_ID = 'currentMerchantId';

    protected const VALIDATION_MESSAGE = 'Merchant reference is already used.';

    /**
     * @var int|null
     */
    protected $currentMerchantId;

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::VALIDATION_MESSAGE;
    }

    /**
     * @return int|null
     */
    public function getCurrentMerchantId(): ?int
    {
        return $this->currentMerchantId;
    }
}
