<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

interface DummyInterface
{

    /**
     * @param string $processName
     *
     * @return mixed
     */
    public function prepareItems($processName);

    /**
     * @param string $processName
     *
     * @return array
     */
    public function getOrderItems($processName);

}
