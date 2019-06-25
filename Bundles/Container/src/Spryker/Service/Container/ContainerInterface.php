<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Container;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ContainerInterface extends PsrContainerInterface
{
    /**
     * @param string $id
     * @param mixed $service
     *
     * @return void
     */
    public function set(string $id, $service): void;

    /**
     * @param string $id
     * @param mixed $service
     *
     * @return void
     */
    public function setGlobal(string $id, $service): void;

    /**
     * @param string $id
     * @param array $configuration
     *
     * @return void
     */
    public function configure(string $id, array $configuration): void;

    /**
     * This one can be used to extend an existing Service without loading it.
     *
     * @param string $id
     * @param \Closure $service
     *
     * @return \Closure
     */
    public function extend(string $id, $service);

    /**
     * Removes an entry from the container.
     *
     * @param string $id
     *
     * @return void
     */
    public function remove(string $id): void;
}
