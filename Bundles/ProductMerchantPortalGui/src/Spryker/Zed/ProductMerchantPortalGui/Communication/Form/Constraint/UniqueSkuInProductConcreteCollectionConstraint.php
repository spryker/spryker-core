<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueSkuInProductConcreteCollectionConstraint extends SymfonyConstraint
{
    /**
     * @var string
     */
    protected const MESSAGE_VALUE_UNIQUE = 'This value needs to be unique.';

    /**
     * @var string
     */
    protected const MESSAGE_VALUE_EXISTS = 'This value already exists.';

    /**
     * @return string
     */
    public function getMessageValueUnique(): string
    {
        return static::MESSAGE_VALUE_UNIQUE;
    }

    /**
     * @return string
     */
    public function getMessageValueExists(): string
    {
        return static::MESSAGE_VALUE_EXISTS;
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }
}
