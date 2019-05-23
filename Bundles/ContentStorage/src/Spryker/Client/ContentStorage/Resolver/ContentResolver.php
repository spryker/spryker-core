<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage\Resolver;

use Spryker\Client\ContentStorage\Exception\MissingContentTermTypePluginException;
use Spryker\Client\ContentStorageExtension\Dependency\Plugin\ContentTermExecutorPluginInterface;

/**
 * @deprecated Will be removed without replacement.
 */
class ContentResolver implements ContentResolverInterface
{
    /**
     * @var array|\Spryker\Client\ContentStorageExtension\Dependency\Plugin\ContentTermExecutorPluginInterface[]
     */
    protected $contentTermPlugins;

    /**
     * @param \Spryker\Client\ContentStorageExtension\Dependency\Plugin\ContentTermExecutorPluginInterface[] $contentTermPlugins
     */
    public function __construct(array $contentTermPlugins)
    {
        $this->contentTermPlugins = $contentTermPlugins;
    }

    /**
     * @return string[]
     */
    public function getTermKeys(): array
    {
        $termKeys = [];
        foreach ($this->contentTermPlugins as $contentPlugin) {
            $termKeys[] = $contentPlugin->getTermKey();
        }

        return $termKeys;
    }

    /**
     * @param string $termKey
     *
     * @throws \Spryker\Client\ContentStorage\Exception\MissingContentTermTypePluginException
     *
     * @return \Spryker\Client\ContentStorageExtension\Dependency\Plugin\ContentTermExecutorPluginInterface
     */
    public function getContentPlugin(string $termKey): ContentTermExecutorPluginInterface
    {
        foreach ($this->contentTermPlugins as $contentPlugin) {
            if ($contentPlugin->getTermKey() === $termKey) {
                return $contentPlugin;
            }
        }

        throw new MissingContentTermTypePluginException(sprintf('There is no registered plugin which can work with the term %s.', $termKey));
    }
}
