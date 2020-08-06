<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Publisher\Business\Collator;

use Spryker\Zed\Publisher\Business\Collator\PublisherEventCollator;

class MockPublishEventCollator extends PublisherEventCollator
{
    /**
     * @return array
     */
    public function getPublisherEventCollection(): array
    {
        return $this->registerEventCollection();
    }
}
