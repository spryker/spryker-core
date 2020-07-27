<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Oauth\ResourceServer\KeyLoader;

class KeyLoader implements KeyLoaderInterface
{
    /**
     * @var \Spryker\Client\OauthExtension\Dependency\Plugin\KeyLoaderPluginInterface[]
     */
    protected $keyLoaderPlugins;

    /**
     * @param \Spryker\Client\OauthExtension\Dependency\Plugin\KeyLoaderPluginInterface[] $keyLoaderPlugins
     */
    public function __construct(array $keyLoaderPlugins)
    {
        $this->keyLoaderPlugins = $keyLoaderPlugins;
    }

    /**
     * @return \League\OAuth2\Server\CryptKey[]
     */
    public function loadKeys(): array
    {
        $collectedKeys = [];

        foreach ($this->keyLoaderPlugins as $keyLoaderPlugin) {
            $collectedKeys[] = $keyLoaderPlugin->loadKeys();
        }

        return $collectedKeys ? array_merge(...$collectedKeys) : [];
    }
}
