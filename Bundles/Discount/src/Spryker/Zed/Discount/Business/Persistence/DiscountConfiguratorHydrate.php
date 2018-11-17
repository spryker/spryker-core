<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Persistence;

use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConditionTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class DiscountConfiguratorHydrate implements DiscountConfiguratorHydrateInterface
{
    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountConfigurationExpanderPluginInterface[]
     */
    protected $discountConfigurationExpanderPlugins;

    /**
     * @var \Spryker\Zed\Discount\Business\Persistence\DiscountEntityMapperInterface
     */
    protected $discountEntityMapper;

    /**
     * @var \Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationMapperInterface
     */
    protected $discountStoreRelationMapper;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     * @param \Spryker\Zed\Discount\Business\Persistence\DiscountEntityMapperInterface $discountEntityMapper
     * @param \Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationMapperInterface $discountStoreRelationMapper
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountConfigurationExpanderPluginInterface[] $discountConfigurationExpanderPlugins
     */
    public function __construct(
        DiscountQueryContainerInterface $discountQueryContainer,
        DiscountEntityMapperInterface $discountEntityMapper,
        DiscountStoreRelationMapperInterface $discountStoreRelationMapper,
        array $discountConfigurationExpanderPlugins
    ) {
        $this->discountQueryContainer = $discountQueryContainer;
        $this->discountEntityMapper = $discountEntityMapper;
        $this->discountStoreRelationMapper = $discountStoreRelationMapper;
        $this->discountConfigurationExpanderPlugins = $discountConfigurationExpanderPlugins;
    }

    /**
     * @deprecated Use `findByIdDiscount()` instead.
     *
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function getByIdDiscount($idDiscount)
    {
        $discountEntity = $this->discountQueryContainer
            ->queryDiscountWithStoresByFkDiscount($idDiscount)
            ->find()
            ->getFirst();

        $discountConfigurator = $this->createDiscountConfiguratorTransfer();

        $discountGeneralTransfer = $this->hydrateGeneralDiscount($discountEntity);
        $discountConfigurator->setDiscountGeneral($discountGeneralTransfer);

        $discountCalculatorTransfer = $this->hydrateDiscountCalculator($discountEntity);
        $discountConfigurator->setDiscountCalculator($discountCalculatorTransfer);

        $discountConditionTransfer = $this->hydrateDiscountCondition($discountEntity);
        $discountConfigurator->setDiscountCondition($discountConditionTransfer);

        $this->hydrateDiscountVoucher($idDiscount, $discountEntity, $discountConfigurator);

        $discountConfigurator = $this->executeDiscountConfigurationExpanderPlugins($discountConfigurator);

        return $discountConfigurator;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return \Generated\Shared\Transfer\DiscountGeneralTransfer
     */
    protected function hydrateGeneralDiscount(SpyDiscount $discountEntity)
    {
        $discountGeneralTransfer = new DiscountGeneralTransfer();
        $discountGeneralTransfer->fromArray($discountEntity->toArray(), true);

        $discountGeneralTransfer->setValidFrom($discountEntity->getValidFrom());
        $discountGeneralTransfer->setValidTo($discountEntity->getValidTo());
        $discountGeneralTransfer->setStoreRelation(
            $this->discountStoreRelationMapper->mapDiscountStoreEntityCollectionToStoreRelationTransfer($discountEntity)
        );

        return $discountGeneralTransfer;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return \Generated\Shared\Transfer\DiscountCalculatorTransfer
     */
    protected function hydrateDiscountCalculator(SpyDiscount $discountEntity)
    {
        $discountCalculatorTransfer = new DiscountCalculatorTransfer();
        $discountCalculatorTransfer->fromArray($discountEntity->toArray(), true);
        $discountCalculatorTransfer->setCollectorStrategyType(DiscountConstants::DISCOUNT_COLLECTOR_STRATEGY_QUERY_STRING);
        $discountCalculatorTransfer->setMoneyValueCollection(
            $this->discountEntityMapper->getMoneyValueCollectionForEntity($discountEntity)
        );

        return $discountCalculatorTransfer;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return \Generated\Shared\Transfer\DiscountConditionTransfer
     */
    protected function hydrateDiscountCondition(SpyDiscount $discountEntity)
    {
        $discountConditionTransfer = new DiscountConditionTransfer();
        $discountConditionTransfer->fromArray($discountEntity->toArray(), true);

        return $discountConditionTransfer;
    }

    /**
     * @param int $idDiscount
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfigurator
     *
     * @return void
     */
    protected function hydrateDiscountVoucher(
        $idDiscount,
        SpyDiscount $discountEntity,
        DiscountConfiguratorTransfer $discountConfigurator
    ) {
        if ($discountEntity->getFkDiscountVoucherPool()) {
            $discountVoucherTransfer = new DiscountVoucherTransfer();
            $discountVoucherTransfer->setIdDiscount($idDiscount);
            $discountVoucherTransfer->setFkDiscountVoucherPool($discountEntity->getFkDiscountVoucherPool());
            $discountConfigurator->setDiscountVoucher($discountVoucherTransfer);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function createDiscountConfiguratorTransfer()
    {
        return new DiscountConfiguratorTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function executeDiscountConfigurationExpanderPlugins(
        DiscountConfiguratorTransfer $discountConfiguratorTransfer
    ) {
        foreach ($this->discountConfigurationExpanderPlugins as $discountConfigurationExpanderPlugin) {
            $discountConfiguratorTransfer = $discountConfigurationExpanderPlugin->expand($discountConfiguratorTransfer);
        }

        return $discountConfiguratorTransfer;
    }

    /**
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer|null
     */
    public function findByIdDiscount(int $idDiscount): ?DiscountConfiguratorTransfer
    {
        $discountEntity = $this->discountQueryContainer
            ->queryDiscountWithStoresByFkDiscount($idDiscount)
            ->find()
            ->getFirst();

        if ($discountEntity === null) {
            return $discountEntity;
        }

        return $this->hydrateDiscountConfigurator($discountEntity);
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function hydrateDiscountConfigurator(SpyDiscount $discountEntity): DiscountConfiguratorTransfer
    {
        $discountConfigurator = $this->createDiscountConfiguratorTransfer();

        $discountGeneralTransfer = $this->hydrateGeneralDiscount($discountEntity);
        $discountCalculatorTransfer = $this->hydrateDiscountCalculator($discountEntity);
        $discountConditionTransfer = $this->hydrateDiscountCondition($discountEntity);

        $discountConfigurator
            ->setDiscountGeneral($discountGeneralTransfer)
            ->setDiscountCalculator($discountCalculatorTransfer)
            ->setDiscountCondition($discountConditionTransfer);

        $this->hydrateDiscountVoucher(
            $discountEntity->getIdDiscount(),
            $discountEntity,
            $discountConfigurator
        );

        $discountConfigurator = $this->executeDiscountConfigurationExpanderPlugins($discountConfigurator);

        return $discountConfigurator;
    }
}
