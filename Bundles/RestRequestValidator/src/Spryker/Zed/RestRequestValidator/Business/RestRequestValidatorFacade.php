<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory getFactory()
 */
class RestRequestValidatorFacade extends AbstractFacade implements RestRequestValidatorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function buildValidationCache(): void
    {
        $this->getFactory()->createRestRequestValidatorCacheBuilder()->build();
    }
}
