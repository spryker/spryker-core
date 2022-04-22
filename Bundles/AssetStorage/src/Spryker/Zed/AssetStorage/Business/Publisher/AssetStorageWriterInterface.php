<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Business\Publisher;

interface AssetStorageWriterInterface
{
    /**
     * @param int $idAsset
     *
     * @return void
     */
    public function publish(int $idAsset): void;

    /**
     * @param int $idAsset
     * @param int $idStore
     *
     * @return void
     */
    public function publishStoreRelation(int $idAsset, int $idStore): void;

    /**
     * @param int $idAsset
     *
     * @return void
     */
    public function unpublish(int $idAsset): void;

    /**
     * @param int $idAsset
     * @param int $idStore
     *
     * @return void
     */
    public function unpublishStoreRelation(int $idAsset, int $idStore): void;
}
