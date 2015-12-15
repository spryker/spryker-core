<?php

namespace Spryker\Zed\Queue\Dependency\Plugin;

use Psr\Log\LoggerInterface;

interface TaskWarmUpPluginInterface
{

    /**
     * @param LoggerInterface $logger
     */
    public function warmUp(LoggerInterface $logger);

}
