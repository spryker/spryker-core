<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\RestRequestValidator\Business\Builder\RestRequestValidatorBuilder;
use Spryker\Zed\RestRequestValidator\Business\Builder\RestRequestValidatorBuilderInterface;

class RestRequestValidatorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\RestRequestValidator\Business\Builder\RestRequestValidatorBuilderInterface
     */
    public function createRestRequestValidatorBuilder(): RestRequestValidatorBuilderInterface
    {
        return new RestRequestValidatorBuilder();
    }
}
