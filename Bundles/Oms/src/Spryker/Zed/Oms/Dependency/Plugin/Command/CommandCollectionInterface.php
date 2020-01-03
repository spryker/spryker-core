<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Dependency\Plugin\Command;

interface CommandCollectionInterface
{
    /**
     * Add new command to list of commands
     *
     * @api
     *
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Command\CommandInterface $command
     * @param string $name
     *
     * @return $this
     */
    public function add($command, $name);

    /**
     * Return command from list of commands
     *
     * @api
     *
     * @param string $name
     *
     * @throws \Spryker\Zed\Oms\Exception\CommandNotFoundException
     *
     * @return \Spryker\Zed\Oms\Dependency\Plugin\Command\CommandInterface
     */
    public function get($name);
}
