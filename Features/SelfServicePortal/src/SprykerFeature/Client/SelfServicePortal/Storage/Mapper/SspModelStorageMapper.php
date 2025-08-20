<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Storage\Mapper;

use Generated\Shared\Transfer\ProductListStorageTransfer;
use Generated\Shared\Transfer\SspModelStorageTransfer;

class SspModelStorageMapper implements SspModelStorageMapperInterface
{
    /**
     * @var string
     */
    protected const KEY_ID_MODEL = 'id_model';

    /**
     * @var string
     */
    protected const KEY_WHITELIST_IDS = 'whitelist_ids';

    /**
     * @param array<string, mixed> $storageData
     *
     * @return \Generated\Shared\Transfer\SspModelStorageTransfer
     */
    public function mapStorageDataToSspModelStorageTransfer(array $storageData): SspModelStorageTransfer
    {
        $sspModelStorageTransfer = new SspModelStorageTransfer();
        $sspModelStorageTransfer->setIdModel($storageData[static::KEY_ID_MODEL] ?? null);

        $sspModelStorageTransfer = $this->mapWhitelists($sspModelStorageTransfer, $storageData);

        return $sspModelStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspModelStorageTransfer $sspModelStorageTransfer
     * @param array<string, mixed> $storageData
     *
     * @return \Generated\Shared\Transfer\SspModelStorageTransfer
     */
    protected function mapWhitelists(
        SspModelStorageTransfer $sspModelStorageTransfer,
        array $storageData
    ): SspModelStorageTransfer {
        if (!isset($storageData[static::KEY_WHITELIST_IDS]) || !is_array($storageData[static::KEY_WHITELIST_IDS])) {
            return $sspModelStorageTransfer;
        }

        foreach ($storageData[static::KEY_WHITELIST_IDS] as $whitelistId) {
            $productListStorageTransfer = (new ProductListStorageTransfer())
                ->setIdProductList($whitelistId);

            $sspModelStorageTransfer->addWhitelist($productListStorageTransfer);
        }

        return $sspModelStorageTransfer;
    }
}
