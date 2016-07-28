<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Builder;

use Generated\Shared\Transfer\FactFinderRequestTransfer;

/**
 * @method \Spryker\Zed\FactFinder\Persistence\FactFinderQueryContainerInterface getQueryContainer()
 */
class BuilderFactory
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

    /**
     * @return \Spryker\Zed\FactFinder\Business\Api\Builder\Head
     */
    public function createHead()
    {
        return new Head(
            $this->requestTransfer
        );
    }

}
