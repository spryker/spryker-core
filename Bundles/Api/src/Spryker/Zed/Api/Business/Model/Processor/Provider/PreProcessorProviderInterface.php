<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Provider;

interface PreProcessorProviderInterface
{

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\FilterPreProcessor
     */
    public function buildFilterPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PaginationPreProcessor
     */
    public function buildPaginationPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PathPreProcessor
     */
    public function buildPathPreProcessor();

}
