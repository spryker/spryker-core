<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage\Resolver;

use Spryker\Client\ContentStorage\Exception\MissingContentTermTypePluginException;
use Spryker\Client\ContentStorageExtension\Plugin\ContentTermExecutorPluginInterface;

class ContentResolver implements ContentResolverInterface
{
    /**
     * @var array|\Spryker\Client\ContentStorageExtension\Plugin\ContentTermExecutorPluginInterface[]
     */
    protected $contentItemTermPlugins;

    /**
     * @param \Spryker\Client\ContentStorageExtension\Plugin\ContentTermExecutorPluginInterface[] $contentItemTermPlugins
     */
    public function __construct(array $contentItemTermPlugins)
    {
        $this->contentItemTermPlugins = $contentItemTermPlugins;
    }

    /**
     * @return string[]
     */
    public function getTermKeys(): array
    {
        $termKeys = [];
        foreach ($this->contentItemTermPlugins as $contentItemPlugin) {
            $termKeys[] = $contentItemPlugin->getTermKey();
        }

        return $termKeys;
    }

    /**
     * @param string $termKey
     *
     * @throws \Spryker\Client\ContentStorage\Exception\MissingContentTermTypePluginException
     *
     * @return \Spryker\Client\ContentStorageExtension\Plugin\ContentTermExecutorPluginInterface
     */
    public function getContentItemPlugin(string $termKey): ContentTermExecutorPluginInterface
    {
        foreach ($this->contentItemTermPlugins as $contentItemPlugin) {
            if ($contentItemPlugin->getTermKey() === $termKey) {
                return $contentItemPlugin;
            }
        }

        throw new MissingContentTermTypePluginException();
    }
}
