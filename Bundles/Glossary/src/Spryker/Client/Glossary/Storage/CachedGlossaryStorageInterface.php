<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Glossary\Storage;

interface CachedGlossaryStorageInterface extends GlossaryStorageInterface
{

    /**
     * @param int $ttl
     *
     * @return void
     */
    public function saveCache($ttl);

}
