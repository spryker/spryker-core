<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Mapper;

use Generated\Shared\Transfer\FactFinderSearchRequestTransfer;

class MapperFactory
{

    /**
     * @var \Generated\Shared\Transfer\FactFinderSearchRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Generated\Shared\Transfer\FactFinderSearchRequestTransfer $requestTransfer
     */
    public function __construct(FactFinderSearchRequestTransfer $requestTransfer)
    {
        $this->requestTransfer = $requestTransfer;
    }

}
