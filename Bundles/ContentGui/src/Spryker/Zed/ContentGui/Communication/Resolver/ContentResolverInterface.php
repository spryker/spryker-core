<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Resolver;

use Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentPluginInterface;

interface ContentResolverInterface
{
    /**
     * @param string $termKey
     *
     * @throws \Spryker\Zed\ContentGui\Communication\Exception\MissingContentTermFormTypePluginException
     *
     * @return \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentPluginInterface
     */
    public function getContentPlugin(string $termKey): ContentPluginInterface;

    /**
     * @return string[]
     */
    public function getTermKeys(): array;
}
