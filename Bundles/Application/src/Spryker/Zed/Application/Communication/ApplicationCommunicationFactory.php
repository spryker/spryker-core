<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication;

use Spryker\Shared\NewRelic\Api;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 */
class ApplicationCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Shared\NewRelic\Api
     */
    public function createNewRelicApi()
    {
        return new Api();
    }

}
