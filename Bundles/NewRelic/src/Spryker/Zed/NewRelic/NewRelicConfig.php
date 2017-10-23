<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NewRelic;

use Spryker\Shared\NewRelic\NewRelicConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class NewRelicConfig extends AbstractBundleConfig
{
    /**
     * @return mixed
     */
    public function getIgnorableTransactions()
    {
        return $this->get(NewRelicConstants::IGNORABLE_TRANSACTIONS, []);
    }
}
