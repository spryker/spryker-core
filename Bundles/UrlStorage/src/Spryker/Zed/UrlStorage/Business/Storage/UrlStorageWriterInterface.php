<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Business\Storage;

interface UrlStorageWriterInterface
{
    /**
     * @param int[] $urlIds
     *
     * @return void
     */
    public function publish(array $urlIds);

    /**
     * @param int[] $urlIds
     *
     * @return void
     */
    public function unpublish(array $urlIds);
}
