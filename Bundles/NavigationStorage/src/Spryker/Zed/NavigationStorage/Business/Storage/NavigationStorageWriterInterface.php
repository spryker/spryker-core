<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationStorage\Business\Storage;

interface NavigationStorageWriterInterface
{
    /**
     * @param array $navigationIds
     *
     * @return void
     */
    public function publish(array $navigationIds);

    /**
     * @param array $navigationIds
     *
     * @return void
     */
    public function unpublish(array $navigationIds);
}
