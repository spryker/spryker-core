<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint;

use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
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
     * @param string $email
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchantByEmail(string $email): ?MerchantTransfer
    {
        $merchantCriteriaFilterTransfer = new MerchantCriteriaFilterTransfer();
        $merchantCriteriaFilterTransfer->setEmail($email);

        return $this->merchantFacade->findOne($merchantCriteriaFilterTransfer);
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
