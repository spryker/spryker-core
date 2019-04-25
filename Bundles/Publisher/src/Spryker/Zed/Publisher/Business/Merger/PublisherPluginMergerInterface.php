<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Business\Merger;

interface PublisherPluginMergerInterface
{
    /**
     * @return array
     */
    public function mergePublisherPlugins(): array;
}
