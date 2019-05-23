<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage\Resolver;

use Spryker\Client\ContentStorageExtension\Dependency\Plugin\ContentTermExecutorPluginInterface;

interface ContentResolverInterface
{
    /**
     * @return string[]
     */
    public function getTermKeys(): array;

    /**
     * @param string $termKey
     *
     * @throws \Spryker\Client\ContentStorage\Exception\MissingContentTermTypePluginException
     *
     * @return \Spryker\Client\ContentStorageExtension\Dependency\Plugin\ContentTermExecutorPluginInterface
     */
    public function getContentPlugin(string $termKey): ContentTermExecutorPluginInterface;
}
