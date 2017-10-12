<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Provider;

interface PostProcessorProviderInterface
{
    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface
     */
    public function buildAddActionPostProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface
     */
    public function buildRemoveActionPostProcessor();
}
