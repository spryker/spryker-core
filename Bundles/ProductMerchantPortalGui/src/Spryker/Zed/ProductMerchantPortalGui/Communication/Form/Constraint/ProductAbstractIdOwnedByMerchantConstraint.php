<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ProductAbstractIdOwnedByMerchantConstraint extends SymfonyConstraint
{
    protected const MESSAGE = 'This abstract product is not owned by this merchant.';

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::MESSAGE;
    }
}
