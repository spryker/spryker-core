<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Resolver;

use Spryker\Zed\ContentGuiExtension\Plugin\ContentPluginInterface;

interface ContentResolverInterface
{
    /**
     * @param string $termKey
     *
     * @throws \Spryker\Zed\ContentGui\Communication\Exception\MissingContentTermFormTypePluginException
     *
     * @return \Spryker\Zed\ContentGuiExtension\Plugin\ContentPluginInterface
     */
    public function getContentItemPlugin(string $termKey): ContentPluginInterface;

    /**
     * @return string[]
     */
    public function getTermKeys(): array;
}
