<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use Spryker\Zed\OmsExtension\Dependency\Plugin\TimeoutProcessorPluginInterface;

interface TimeoutProcessorCollectionInterface
{
    /**
     * @param string $name
     *
     * @throws \Spryker\Zed\Oms\Business\Exception\TimeoutProcessorPluginNotFoundException
     *
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\TimeoutProcessorPluginInterface
     */
    public function get(string $name): TimeoutProcessorPluginInterface;
}
