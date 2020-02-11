<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Persistence;

interface UrlStorageEntityManagerInterface
{
    /**
     * @param array $urlIds
     *
     * @return void
     */
    public function deleteStorageUrlsByIds(array $urlIds): void;
}
