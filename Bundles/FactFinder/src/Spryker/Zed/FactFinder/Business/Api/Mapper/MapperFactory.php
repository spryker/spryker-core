<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Mapper;

use Generated\Shared\Transfer\FfSearchRequestTransfer;

class MapperFactory
{

    /**
     * @var \Generated\Shared\Transfer\FfSearchRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Generated\Shared\Transfer\FfSearchRequestTransfer $requestTransfer
     */
    public function __construct(FfSearchRequestTransfer $requestTransfer)
    {
        $this->requestTransfer = $requestTransfer;
    }

}
