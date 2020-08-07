<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form\Constraint;

use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToUrlFacadeInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueUrl extends SymfonyConstraint
{
    public const OPTION_URL_FACADE = 'urlFacade';

    /**
     * @var \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToUrlFacadeInterface
     */
    protected $urlFacade;

    /**
     * @return \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToUrlFacadeInterface
     */
    public function getUrlFacade(): MerchantGuiToUrlFacadeInterface
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
