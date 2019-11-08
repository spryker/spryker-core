<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Publisher\Business\Collator;

use Spryker\Zed\Publisher\Business\Collator\PublisherPluginCollator;

class MockPublishPluginCollator extends PublisherPluginCollator
{
    /**
     * @return array
     */
    public function getPublisherPlugins(): array
    {
        return $this->getPublisherEventCollection();
    }
}
