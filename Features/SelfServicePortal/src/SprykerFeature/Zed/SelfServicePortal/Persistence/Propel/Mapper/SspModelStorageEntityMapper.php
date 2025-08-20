<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SspModelTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelStorage;

class SspModelStorageEntityMapper implements SspModelStorageEntityMapperInterface
{
    /**
     * @var string
     */
    protected const KEY_ID_MODEL = 'id_model';

    /**
     * @var string
     */
    protected const KEY_WHITELIST_IDS = 'whitelist_ids';

    public function mapSspModelTransferToSspModelStorageEntity(
        SspModelTransfer $sspModelTransfer,
        SpySspModelStorage $sspModelStorageEntity
    ): SpySspModelStorage {
        $sspModelStorageEntity->setFkSspModel($sspModelTransfer->getIdSspModelOrFail());
        $sspModelStorageEntity->setData($this->mapSspModelTransferToStorageData($sspModelTransfer));

        return $sspModelStorageEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SspModelTransfer $sspModelTransfer
     *
     * @return array<string, mixed>
     */
    protected function mapSspModelTransferToStorageData(SspModelTransfer $sspModelTransfer): array
    {
        $whitelistIds = $this->extractWhitelistIds($sspModelTransfer);

        return [
            static::KEY_ID_MODEL => $sspModelTransfer->getIdSspModel(),
            static::KEY_WHITELIST_IDS => $whitelistIds,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\SspModelTransfer $sspModelTransfer
     *
     * @return list<int>
     */
    protected function extractWhitelistIds(SspModelTransfer $sspModelTransfer): array
    {
        $whitelistIds = [];
        foreach ($sspModelTransfer->getProductLists() as $productListTransfer) {
            $whitelistIds[] = $productListTransfer->getIdProductListOrFail();
        }

        return $whitelistIds;
    }
}
