<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class HtmlTagWhitelistConstraint extends SymfonyConstraint
{
    /**
     * @var string[]
     */
    protected $allowedHtmlTags = [];

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }

    /**
     * @return string[]
     */
    public function getAllowedHtmlTags(): array
    {
        return $this->allowedHtmlTags;
    }
}
