<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NewRelic\Business;

/**
 * @method \Spryker\Zed\NewRelic\Business\NewRelicBusinessFactory getFactory()
 */
interface NewRelicFacadeInterface
{
    /**
     * @api
     *
     * @param array $arguments
     *
     * @return \Spryker\Zed\NewRelic\Business\Model\RecordDeploymentInterface
     */
    public function recordDeployment(array $arguments = []);
}
