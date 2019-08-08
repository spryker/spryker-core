<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Business\Storage;

interface CategoryImageStorageWriterInterface
{
    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function publish(array $categoryIds): void;

    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function unpublish(array $categoryIds): void;
}
