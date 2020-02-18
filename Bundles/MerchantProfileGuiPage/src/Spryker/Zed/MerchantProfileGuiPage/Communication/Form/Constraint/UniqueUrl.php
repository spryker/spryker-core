<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint;

use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToUrlFacadeInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueUrl extends SymfonyConstraint
{
    public const OPTION_URL_FACADE = 'urlFacade';

    /**
     * @var \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToUrlFacadeInterface
     */
    protected $urlFacade;

    /**
     * @return \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToUrlFacadeInterface
     */
    public function getUrlFacade(): MerchantProfileGuiPageToUrlFacadeInterface
    {
        return $this->urlFacade;
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }
}
