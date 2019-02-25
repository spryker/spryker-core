<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\LocaleExtension\Dependency\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Service\Container\ContainerInterface;

interface LocalePluginInterface
{
    /**
     * Specification:
     * - Returns a LocaleTransfer.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleTransfer(ContainerInterface $container): LocaleTransfer;
}
