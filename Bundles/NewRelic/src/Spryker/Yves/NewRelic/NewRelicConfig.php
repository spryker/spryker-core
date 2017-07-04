<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\NewRelic;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class NewRelicConfig extends AbstractBundleConfig
{

    /**
     * @return array
     */
    public function getIgnorableTransactionRouteNames()
    {
        return [];
    }
}
