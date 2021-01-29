<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Container;

use Spryker\Service\Container\ContainerInterface;

interface GlobalContainerInterface
{
    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return void
     */
    public static function setContainer(ContainerInterface $container): void;

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function getContainer(): ContainerInterface;

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function has(string $id);

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function get(string $id);
}
