<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Communication\Plugin\Processor;

use Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Log\Communication\LogCommunicationFactory getFactory()
 * @method \Spryker\Zed\Log\LogConfig getConfig()
 * @method \Spryker\Zed\Log\Business\LogFacadeInterface getFacade()
 */
class PsrLogMessageProcessorPlugin extends AbstractPlugin implements LogProcessorPluginInterface
{
    /**
     * @api
     *
     * @param array $data
     *
     * @return array
     */
    public function __invoke(array $data)
    {
        return $this->getFactory()->createPsrMessageProcessor()->__invoke($data);
    }
}
