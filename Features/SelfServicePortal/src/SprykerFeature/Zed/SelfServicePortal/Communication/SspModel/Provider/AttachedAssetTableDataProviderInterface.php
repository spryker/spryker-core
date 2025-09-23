<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Provider;

interface AttachedAssetTableDataProviderInterface
{
    /**
     * @param int $idSspModel
     *
     * @return array<string, mixed>
     */
    public function getAttachedAssetTableData(int $idSspModel): array;
}
