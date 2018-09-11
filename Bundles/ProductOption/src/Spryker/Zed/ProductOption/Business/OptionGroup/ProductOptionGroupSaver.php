<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductAbstractProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToEventFacadeInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeInterface;
use Spryker\Zed\ProductOption\Dependency\ProductOptionEvents;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use Spryker\Zed\ProductOption\ProductOptionConfig;

class ProductOptionGroupSaver implements ProductOptionGroupSaverInterface
{
    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $productOptionQueryContainer;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface
     */
    protected $translationSaver;

    /**
     * @var \Spryker\Zed\ProductOption\Business\OptionGroup\AbstractProductOptionSaverInterface
     */
    protected $abstractProductOptionSaver;

    /**
     * @var \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueSaverInterface
     */
    protected $productOptionValueSaver;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeInterface $touchFacade
     * @param \Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface $translationSaver
     * @param \Spryker\Zed\ProductOption\Business\OptionGroup\AbstractProductOptionSaverInterface $abstractProductOptionSaver
     * @param \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueSaverInterface $productOptionValueSaver
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToEventFacadeInterface $eventFacade
     */
    public function __construct(
        ProductOptionQueryContainerInterface $productOptionQueryContainer,
        ProductOptionToTouchFacadeInterface $touchFacade,
        TranslationSaverInterface $translationSaver,
        AbstractProductOptionSaverInterface $abstractProductOptionSaver,
        ProductOptionValueSaverInterface $productOptionValueSaver,
        ProductOptionToEventFacadeInterface $eventFacade
    ) {
        $this->productOptionQueryContainer = $productOptionQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->translationSaver = $translationSaver;
        $this->abstractProductOptionSaver = $abstractProductOptionSaver;
        $this->productOptionValueSaver = $productOptionValueSaver;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return int
     */
    public function saveProductOptionGroup(ProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        $productOptionGroupEntity = $this->getProductOptionGroupEntity($productOptionGroupTransfer);
        $this->hydrateProductOptionGroupEntity($productOptionGroupTransfer, $productOptionGroupEntity);
        $productOptionGroupEntity->save();

        $this->productOptionValueSaver->saveOptionValues($productOptionGroupTransfer, $productOptionGroupEntity);
        $this->productOptionValueSaver->removeOptionValues($productOptionGroupTransfer, $productOptionGroupEntity);

        $this->abstractProductOptionSaver->assignProducts($productOptionGroupTransfer, $productOptionGroupEntity);
        $this->abstractProductOptionSaver->deAssignProducts($productOptionGroupTransfer, $productOptionGroupEntity);

        $this->translationSaver->addGroupNameTranslations($productOptionGroupTransfer);
        $this->translationSaver->addValueTranslations($productOptionGroupTransfer);

        $this->touchProductOptionGroupAbstractProducts($productOptionGroupEntity);

        $productOptionGroupEntity->save();

        $productOptionGroupTransfer->setIdProductOptionGroup($productOptionGroupEntity->getIdProductOptionGroup());

        return $productOptionGroupEntity->getIdProductOptionGroup();
    }

    /**
     * @param int $idProductOptionGroup
     * @param bool $isActive
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException
     *
     * @return bool
     */
    public function toggleOptionActive($idProductOptionGroup, $isActive)
    {
        $productOptionGroupEntity = $this->getOptionGroupById($idProductOptionGroup);

        if (!$productOptionGroupEntity) {
            throw new ProductOptionGroupNotFoundException(
                sprintf('Product option group with id "%d" not found', $idProductOptionGroup)
            );
        }

        $this->touchProductOptionGroupAbstractProducts($productOptionGroupEntity, $isActive);

        $productOptionGroupEntity->setActive($isActive);

        return $productOptionGroupEntity->save() > 0;
    }

    /**
     * @param int $idProductOptionGroup
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup
     */
    protected function getOptionGroupById($idProductOptionGroup)
    {
        $productOptionGroupEntity = $this->productOptionQueryContainer
            ->queryProductOptionGroupById($idProductOptionGroup)
            ->findOne();

        return $productOptionGroupEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return void
     */
    protected function hydrateProductOptionGroupEntity(
        ProductOptionGroupTransfer $productOptionGroupTransfer,
        SpyProductOptionGroup $productOptionGroupEntity
    ) {

        if ($productOptionGroupTransfer->getName() &&
            strpos($productOptionGroupTransfer->getName(), ProductOptionConfig::PRODUCT_OPTION_GROUP_NAME_TRANSLATION_PREFIX) === false) {
            $productOptionGroupTransfer->setName(
                ProductOptionConfig::PRODUCT_OPTION_GROUP_NAME_TRANSLATION_PREFIX . $productOptionGroupTransfer->getName()
            );
        }

        $productOptionGroupEntity->fromArray($productOptionGroupTransfer->toArray());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup
     */
    protected function getProductOptionGroupEntity(ProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        if ($productOptionGroupTransfer->getIdProductOptionGroup()) {
            return $this->getOptionGroupById($productOptionGroupTransfer->getIdProductOptionGroup());
        }

        return $this->createProductOptionGroupEntity();
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     * @param bool|null $isActive
     *
     * @return void
     */
    protected function touchProductOptionGroupAbstractProducts(SpyProductOptionGroup $productOptionGroupEntity, ?bool $isActive = null): void
    {
        foreach ($productOptionGroupEntity->getSpyProductAbstractProductOptionGroups() as $productAbstractProductOptionEntity) {
            $idProductAbstract = $productAbstractProductOptionEntity->getFkProductAbstract();

            if ($isActive === false && $productAbstractProductOptionEntity->getSpyProductOptionGroup()->isActive() !== $isActive) {
                $this->triggerProductOptionGroupDeleteEvent($idProductAbstract);
            }

            $this->touchFacade->touchActive(
                ProductOptionConfig::RESOURCE_TYPE_PRODUCT_OPTION,
                $idProductAbstract
            );
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function triggerProductOptionGroupDeleteEvent(int $idProductAbstract): void
    {
        $eventTransfer = (new EventEntityTransfer())->setForeignKeys([
            SpyProductAbstractProductOptionGroupTableMap::COL_FK_PRODUCT_ABSTRACT => $idProductAbstract,
        ]);

        $this->eventFacade->trigger(
            ProductOptionEvents::ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_DELETE,
            $eventTransfer
        );
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup
     */
    protected function createProductOptionGroupEntity()
    {
        return new SpyProductOptionGroup();
    }
}
