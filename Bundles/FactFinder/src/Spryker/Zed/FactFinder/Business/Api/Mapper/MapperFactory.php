<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Mapper;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\FactFinder\FactFinderConfig getConfig()
 */
class MapperFactory extends AbstractBusinessFactory
{

    /**
     * @var \Generated\Shared\Transfer\FactFinderRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Generated\Shared\Transfer\FactFinderRequestTransfer $requestTransfer
     */
    public function __construct(FactFinderRequestTransfer $requestTransfer)
    {
        $this->requestTransfer = $requestTransfer;
    }

}
