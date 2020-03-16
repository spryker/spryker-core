<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint;

use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Symfony\Component\Validator\Constraint;

class UniqueMerchantReference extends Constraint
{
    public const OPTION_MERCHANT_FACADE = 'merchantFacade';
    public const OPTION_CURRENT_MERCHANT_ID = 'currentMerchantId';

    protected const VALIDATION_MESSAGE = 'Merchant reference is already used.';

    /**
     * @var \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToMerchantFacadeInterface
     */
    protected $merchantFacade;

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

    /**
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchantByReference(string $merchantReference): ?MerchantTransfer
    {
        $merchantCriteriaFilterTransfer = new MerchantCriteriaFilterTransfer();
        $merchantCriteriaFilterTransfer->setMerchantReference($merchantReference);

        return $this->merchantFacade->findOne($merchantCriteriaFilterTransfer);
    }
}
