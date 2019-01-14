<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Resolver;

use Spryker\Zed\ContentGui\Communication\Exception\MissingContentTermFormTypePluginException;
use Spryker\Zed\ContentGuiExtension\Plugin\ContentPluginInterface;

class ContentResolver implements ContentResolverInterface
{
    /**
     * @var array|\Spryker\Zed\ContentGuiExtension\Plugin\ContentPluginInterface[]
     */
    protected $contentItemPlugins;

    /**
     * @param \Spryker\Zed\ContentGuiExtension\Plugin\ContentPluginInterface[] $contentItemPlugins
     */
    public function __construct(array $contentItemPlugins)
    {
        $this->contentItemPlugins = $contentItemPlugins;
    }

    /**
     * @return string[]
     */
    public function getTermKeys(): array
    {
        $termKeys = [];
        foreach ($this->contentItemPlugins as $contentItemPlugin) {
            $termKeys[] = $contentItemPlugin->getTermKey();
        }

        return $termKeys;
    }

    /**
     * @param string $termKey
     *
     * @throws \Spryker\Zed\ContentGui\Communication\Exception\MissingContentTermFormTypePluginException
     *
     * @return \Spryker\Zed\ContentGuiExtension\Plugin\ContentPluginInterface
     */
    public function getContentItemPlugin(string $termKey): ContentPluginInterface
    {
        foreach ($this->contentItemPlugins as $contentItemPlugin) {
            if ($contentItemPlugin->getTermKey() === $termKey) {
                return $contentItemPlugin;
            }
        }

        throw new MissingContentTermFormTypePluginException();
    }
}
