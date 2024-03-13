<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Dependency\Facade;

use Symfony\Component\Console\Output\OutputInterface;

class TestifyBackendApiToQueueFacadeBridge implements TestifyBackendApiToQueueFacadeInterface
{
    /**
     * @var \Spryker\Zed\Queue\Business\QueueFacadeInterface
     */
    protected $queueFacade;

    /**
     * @param \Spryker\Zed\Queue\Business\QueueFacadeInterface $queueFacade
     */
    public function __construct($queueFacade)
    {
        $this->queueFacade = $queueFacade;
    }

    /**
     * @param string $command
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function startWorker(string $command, OutputInterface $output, array $options = []): void
    {
         $this->queueFacade->startWorker($command, $output, $options);
    }
}
