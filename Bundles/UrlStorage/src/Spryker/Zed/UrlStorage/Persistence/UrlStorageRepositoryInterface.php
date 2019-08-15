<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Persistence;

interface UrlStorageRepositoryInterface
{
    /**
     * @param int[] $urlIds
     * @param string[] $localeNames
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl[][]
     */
    public function findLocalizedUrlsByUrlIds(array $urlIds, array $localeNames): array;

    /**
     * @param int[] $urlIds
     *
     * @return \Orm\Zed\UrlStorage\Persistence\SpyUrlStorage[]
     */
    public function findUrlStorageByUrlIds(array $urlIds): array;
}
