<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NewRelic\Communication;

use Spryker\Shared\NewRelic\Api;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\NewRelic\NewRelicConfig getConfig()
 */
class NewRelicCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Shared\NewRelic\Api
     */
    public function createNewRelicApi()
    {
        return new Api();
    }

}
