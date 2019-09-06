<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence;

use Generated\Shared\Transfer\PriceProductScheduleCriteriaFilterTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Propel\PropelConfig;

/**
 * @method \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductSchedulePersistenceFactory getFactory()
 */
class PriceProductScheduleRepository extends AbstractRepository implements PriceProductScheduleRepositoryInterface
{
    protected const COL_PRODUCT_ID = 'product_id';
    protected const COL_RESULT = 'result';

    protected const ALIAS_CONCATENATED = 'concatenated';
    protected const ALIAS_FILTERED = 'filtered';

    protected const MESSAGE_NOT_SUPPORTED_DB_ENGINE = 'DB engine "%s" is not supported. Please extend EXPRESSION_CONCATENATED_RESULT_MAP';

    protected const EXPRESSION_CONCATENATED_RESULT_MAP = [
        PropelConfig::DB_ENGINE_PGSQL => 'CAST(CONCAT(CONCAT(CAST(EXTRACT(epoch from now() - %s) + EXTRACT(epoch from %s - now()) AS INT), \'.\'), %s + %s) as DECIMAL)',
        PropelConfig::DB_ENGINE_MYSQL => 'CONCAT(CONCAT(CAST(TIMESTAMPDIFF(minute, %s, now()) + TIMESTAMPDIFF(minute, now(), %s) AS BINARY), \'.\'), %s + %s) + 0',
    ];

    protected const EXPRESSION_CONCATENATED_PRODUCT_ID = 'CONCAT(%s, \' \', %s, \' \', COALESCE(%s, 0), \'_\', COALESCE(%s, 0))';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleMapperInterface
     */
    protected $priceProductScheduleMapper;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPropelFacadeInterface
     */
    protected $propelFacade;

    public function __construct()
    {
        $this->priceProductScheduleMapper = $this->getFactory()->createPriceProductScheduleMapper();
        $this->propelFacade = $this->getFactory()->getPropelFacade();
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToDisable(): array
    {
        return $this->getFactory()
            ->createPriceProductScheduleDisableFinder()
            ->findPriceProductSchedulesToDisable();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToDisableByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->getFactory()
            ->createPriceProductScheduleDisableFinder()
            ->findPriceProductSchedulesToDisableByIdProductAbstract($idProductAbstract);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToDisableByIdProductConcrete(int $idProductConcrete): array
    {
        return $this->getFactory()
            ->createPriceProductScheduleDisableFinder()
            ->findPriceProductSchedulesToDisableByIdProductConcrete($idProductConcrete);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findSimilarPriceProductSchedulesToDisable(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): array {
        return $this->getFactory()
            ->createPriceProductScheduleDisableFinder()
            ->findSimilarPriceProductSchedulesToDisable($priceProductScheduleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToEnableByStore(StoreTransfer $storeTransfer): array
    {
        return $this->getFactory()
            ->createPriceProductScheduleEnableFinder()
            ->findPriceProductSchedulesToEnableByStore($storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToEnableByStoreAndIdProductAbstract(
        StoreTransfer $storeTransfer,
        int $idProductAbstract
    ): array {
        return $this->getFactory()
            ->createPriceProductScheduleEnableFinder()
            ->findPriceProductSchedulesToEnableByStoreAndIdProductAbstract($storeTransfer, $idProductAbstract);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToEnableByStoreAndIdProductConcrete(
        StoreTransfer $storeTransfer,
        int $idProductConcrete
    ): array {
        return $this->getFactory()
            ->createPriceProductScheduleEnableFinder()
            ->findPriceProductSchedulesToEnableByStoreAndIdProductConcrete($storeTransfer, $idProductConcrete);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleCriteriaFilterTransfer $priceProductScheduleCriteriaFilterTransfer
     *
     * @return int
     */
    public function findCountPriceProductScheduleByCriteriaFilter(
        PriceProductScheduleCriteriaFilterTransfer $priceProductScheduleCriteriaFilterTransfer
    ): int {
        return $this->getFactory()
            ->createPriceProductScheduleFinder()
            ->findCountPriceProductScheduleByCriteriaFilter($priceProductScheduleCriteriaFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer|null
     */
    public function findPriceProductScheduleListById(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): ?PriceProductScheduleListTransfer {
        return $this->getFactory()
            ->createPriceProductScheduleListFinder()
            ->findPriceProductScheduleListById($priceProductScheduleListTransfer);
    }

    /**
     * @param int $idPriceProductSchedule
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer|null
     */
    public function findPriceProductScheduleById(int $idPriceProductSchedule): ?PriceProductScheduleTransfer
    {
        return $this->getFactory()
            ->createPriceProductScheduleFinder()
            ->findPriceProductScheduleById($idPriceProductSchedule);
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer|null
     */
    public function findPriceProductScheduleListByName(string $name): ?PriceProductScheduleListTransfer
    {
        return $this->getFactory()
            ->createPriceProductScheduleListFinder()
            ->findPriceProductScheduleListByName($name);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return bool
     */
    public function isPriceProductScheduleUnique(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): bool {
        return $this->getFactory()
            ->createPriceProductScheduleFinder()
            ->isPriceProductScheduleUnique($priceProductScheduleTransfer);
    }
}
