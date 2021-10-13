<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueConcreteSkuCollectionConstraint extends SymfonyConstraint
{
    /**
     * @var string
     */
    protected const MESSAGE = 'SKU Prefix already exists in collection';

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::MESSAGE;
    }
}
