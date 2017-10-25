<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NewRelic\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\NewRelic\Business\Model\RecordDeployment;

/**
 * @method \Spryker\Zed\NewRelic\NewRelicConfig getConfig()
 */
class NewRelicBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\NewRelic\Business\Model\RecordDeploymentInterface
     */
    public function createRecordDeployment()
    {
        return new RecordDeployment();
    }
}
