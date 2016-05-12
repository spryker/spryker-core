<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Glossary;

interface GlossaryClientInterface
{

    /**
     * @api
     *
     * @param string $id
     * @param string $localeName
     * @param array $parameters
     *
     * @return string
     */
    public function translate($id, $localeName, array $parameters = []);

    /**
     * @param string $id
     * @param string $localeName
     * @param string $requestCacheKey
     * @param array $parameters
     *
     * @return string
     */
    public function cachedTranslate($id, $localeName, $requestCacheKey, array $parameters = []);

    /**
     * @param string $localeName
     * @param string $requestCacheKey
     * @param int|null $ttl
     *
     * @return void
     */
    public function saveCache($localeName, $requestCacheKey, $ttl = null);

}
