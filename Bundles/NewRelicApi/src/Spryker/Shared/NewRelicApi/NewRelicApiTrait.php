<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\NewRelicApi;

trait NewRelicApiTrait
{
    /**
     * @return \Spryker\Shared\NewRelicApi\NewRelicApiInterface
     */
    public function createNewRelicApi()
    {
        return new NewRelicApi();
    }
}
