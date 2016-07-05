<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Builder;

use Generated\Shared\Transfer\BuilderFactoryRequestTransfer;

/**
 * @package Spryker\Zed\BuilderFactory\Business\Api\Builder
 */
abstract class AbstractBuilder
{

    /**
     * @var \Generated\Shared\Transfer\BuilderFactoryRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Generated\Shared\Transfer\BuilderFactoryRequestTransfer $requestTransfer
     */
    public function __construct(BuilderFactoryRequestTransfer $requestTransfer)
    {
        $this->requestTransfer = $requestTransfer;
    }

    

}
