<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Business\Storage;

interface MerchantProfileStorageWriterInterface
{
    /**
     * @param int[] $merchantProfileIds
     *
     * @return void
     */
    public function publish(array $merchantProfileIds): void;

    /**
     * @param int[] $merchantProfileIds
     *
     * @return void
     */
    public function unpublish(array $merchantProfileIds): void;
}
