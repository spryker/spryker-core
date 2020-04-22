<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Propel\Runtime\Collection\ObjectCollection;

class MerchantMapper implements MerchantMapperInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Persistence\Propel\Mapper\MerchantStoreMapper
     */
    protected $merchantStoreMapper;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\Propel\Mapper\MerchantStoreMapper $merchantStoreMapper
     */
    public function __construct(MerchantStoreMapper $merchantStoreMapper)
    {
        $this->merchantStoreMapper = $merchantStoreMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchant $merchantEntity
     *
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchant
     */
    public function mapMerchantTransferToMerchantEntity(
        MerchantTransfer $merchantTransfer,
        SpyMerchant $merchantEntity
    ): SpyMerchant {
        $merchantEntity->fromArray(
            $merchantTransfer->modifiedToArray(false)
        );

        return $merchantEntity;
    }

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchant $merchantEntity
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function mapMerchantEntityToMerchantTransfer(
        SpyMerchant $merchantEntity,
        MerchantTransfer $merchantTransfer
    ): MerchantTransfer {
        $merchantTransfer = $merchantTransfer->fromArray(
            $merchantEntity->toArray(),
            true
        );

        $merchantTransfer = $this->mapStoreEntitiesToMerchantTransfer($merchantEntity->getSpyMerchantStoresJoinSpyStore(), $merchantTransfer);

        $this->mapUrlCollectionToMerchantTransfer($merchantEntity->getSpyUrls(), $merchantTransfer);

        return $merchantTransfer;
    }

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchant[] $merchantEntities
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function mapMerchantCollectionToMerchantCollectionTransfer(
        $merchantEntities,
        MerchantCollectionTransfer $merchantCollectionTransfer
    ): MerchantCollectionTransfer {
        $merchants = new ArrayObject();

        foreach ($merchantEntities as $merchantEntity) {
            $merchants->append($this->mapMerchantEntityToMerchantTransfer($merchantEntity, new MerchantTransfer()));
        }

        $merchantCollectionTransfer->setMerchants($merchants);

        return $merchantCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Url\Persistence\SpyUrl[] $urlEntities
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function mapUrlCollectionToMerchantTransfer(ObjectCollection $urlEntities, MerchantTransfer $merchantTransfer)
    {
        $urlTransfers = new ArrayObject();
        foreach ($urlEntities as $urlEntity) {
            $urlTransfer = (new UrlTransfer())->fromArray($urlEntity->toArray(), true);
            $urlTransfer->setLocaleName($urlEntity->getSpyLocale()->getLocaleName());

            $urlTransfers->append($urlTransfer);
        }

        $merchantTransfer->setUrlCollection($urlTransfers);

        return $merchantTransfer;
    }

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchantStore[]|\Propel\Runtime\Collection\ObjectCollection $merchantStoreEntities
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function mapStoreEntitiesToMerchantTransfer(ObjectCollection $merchantStoreEntities, MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $storeTransfers = new ArrayObject();
        foreach ($merchantStoreEntities as $merchantStoreEntity) {
            $storeTransfers->append($this->merchantStoreMapper->mapStoreEntityToStoreTransfer($merchantStoreEntity->getSpyStore(), new StoreTransfer()));
        }

        $storeRelationTransfer = (new StoreRelationTransfer())
            ->setIdEntity($merchantTransfer->getIdMerchant());

        $storeRelationTransfer = $this->merchantStoreMapper->mapStoreTransfersToStoreRelationTransfer($storeTransfers, $storeRelationTransfer);

        $merchantTransfer->setStoreRelation($storeRelationTransfer);

        return $merchantTransfer;
    }
}
