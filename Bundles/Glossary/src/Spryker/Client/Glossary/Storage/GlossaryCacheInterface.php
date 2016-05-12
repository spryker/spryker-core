<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Glossary\Storage;

interface GlossaryCacheInterface
{

    /**
     * @param array $translations
     * @param int|null $ttl
     *
     * @return void
     */
    public function saveCache(array $translations, $ttl = null);

    /**
     * @return array
     */
    public function loadCache();

}
