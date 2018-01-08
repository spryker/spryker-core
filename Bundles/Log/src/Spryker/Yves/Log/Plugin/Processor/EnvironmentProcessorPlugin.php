<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Log\Plugin\Processor;

use Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Log\LogFactory getFactory()
 */
class EnvironmentProcessorPlugin extends AbstractPlugin implements LogProcessorPluginInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function __invoke(array $data)
    {
        return $this->getFactory()->createEnvironmentProcessorPublic()->__invoke($data);
    }
}
