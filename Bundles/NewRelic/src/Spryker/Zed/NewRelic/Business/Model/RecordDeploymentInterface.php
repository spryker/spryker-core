<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NewRelic\Business\Model;

interface RecordDeploymentInterface
{
    /**
     * @param array $arguments
     *
     * @throws \Spryker\Zed\NewRelic\Business\Exception\RecordDeploymentException
     *
     * @return $this
     */
    public function recordDeployment(array $arguments = []);
}
