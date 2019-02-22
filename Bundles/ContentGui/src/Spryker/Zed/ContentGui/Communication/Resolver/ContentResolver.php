<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Resolver;

use Spryker\Zed\ContentGui\Communication\Exception\MissingContentTermFormTypePluginException;
use Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentPluginInterface;

class ContentResolver implements ContentResolverInterface
{
    /**
     * @var array|\Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentPluginInterface[]
     */
    protected $contentPlugins;

    /**
     * @param \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentPluginInterface[] $contentPlugins
     */
    public function __construct(array $contentPlugins)
    {
        $this->contentPlugins = $contentPlugins;
    }

    /**
     * @return string[]
     */
    public function getTermKeys(): array
    {
        $termKeys = [];
        foreach ($this->contentPlugins as $contentPlugin) {
            $termKeys[] = $contentPlugin->getTermKey();
        }

        return $termKeys;
    }

    /**
     * @param string $termKey
     *
     * @throws \Spryker\Zed\ContentGui\Communication\Exception\MissingContentTermFormTypePluginException
     *
     * @return \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentPluginInterface
     */
    public function getContentPlugin(string $termKey): ContentPluginInterface
    {
        foreach ($this->contentPlugins as $contentPlugin) {
            if ($contentPlugin->getTermKey() === $termKey) {
                return $contentPlugin;
            }
        }

        throw new MissingContentTermFormTypePluginException(sprintf('There is no registered plugin which can work with the term %s.', $termKey));
    }
}
