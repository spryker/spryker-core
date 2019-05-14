<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Publisher\Business\Merger;

use Spryker\Zed\Publisher\Business\Merger\PublisherPluginMerger;

class MockPluginMerger extends PublisherPluginMerger
{
    /**
     * @return array
     */
    public function getPublisherPlugins(): array
    {
        return $this->getPublisherEventCollection();
    }
}
