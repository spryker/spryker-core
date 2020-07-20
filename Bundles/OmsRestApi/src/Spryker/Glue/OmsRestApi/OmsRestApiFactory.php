<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OmsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\OmsRestApi\Processor\Mapper\OmsStateMapper;
use Spryker\Glue\OmsRestApi\Processor\Mapper\OmsStateMapperInterface;

class OmsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\OmsRestApi\Processor\Mapper\OmsStateMapperInterface
     */
    public function createOmsStateMapper(): OmsStateMapperInterface
    {
        return new OmsStateMapper();
    }
}
