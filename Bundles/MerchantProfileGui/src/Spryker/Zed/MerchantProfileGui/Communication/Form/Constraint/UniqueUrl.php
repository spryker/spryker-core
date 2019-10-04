<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Form\Constraint;

use Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToUrlFacadeInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueUrl extends SymfonyConstraint
{
    public const OPTION_URL_FACADE = 'urlFacade';

    /**
     * @var \Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToUrlFacadeInterface
     */
    protected $urlFacade;

    /**
     * @return \Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToUrlFacadeInterface
     */
    public function getUrlFacade(): MerchantProfileGuiToUrlFacadeInterface
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
