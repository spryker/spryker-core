<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Provider;

interface AttachedSspAssetTableDataProviderInterface
{
    /**
     * @param int $idSspModel
     *
     * @return array<string, mixed>
     */
    public function getAttachedSspAssetTableData(int $idSspModel): array;
}
