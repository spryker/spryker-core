<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlot;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundlePersistenceFactory getFactory()
 */
class ConfigurableBundleEntityManager extends AbstractEntityManager implements ConfigurableBundleEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function createConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateTransfer {
        $configurableBundleTemplateEntity = $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapConfigurableBundleTemplateTransferToEntity($configurableBundleTemplateTransfer, new SpyConfigurableBundleTemplate());

        $configurableBundleTemplateEntity->save();
        $configurableBundleTemplateTransfer->setIdConfigurableBundleTemplate(
            $configurableBundleTemplateEntity->getIdConfigurableBundleTemplate()
        );

        return $configurableBundleTemplateTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function updateConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateTransfer {
        $configurableBundleTemplateEntity = $this->getFactory()
            ->createConfigurableBundleTemplateQuery()
            ->findOneByIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        $configurableBundleTemplateEntity = $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapConfigurableBundleTemplateTransferToEntity($configurableBundleTemplateTransfer, $configurableBundleTemplateEntity);

        $configurableBundleTemplateEntity->save();

        return $configurableBundleTemplateTransfer;
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function deleteConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void
    {
        $configurableBundleTemplateEntity = $this->getFactory()
            ->createConfigurableBundleTemplateQuery()
            ->filterByIdConfigurableBundleTemplate($idConfigurableBundleTemplate)
            ->findOne();

        if (!$configurableBundleTemplateEntity) {
            return;
        }

        $configurableBundleTemplateEntity->delete();
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function deleteConfigurableBundleTemplateSlotsByIdTemplate(int $idConfigurableBundleTemplate): void
    {
        $configurableBundleTemplateSlotEntities = $this->getFactory()
            ->createConfigurableBundleTemplateSlotQuery()
            ->filterByFkConfigurableBundleTemplate($idConfigurableBundleTemplate)
            ->find();

        foreach ($configurableBundleTemplateSlotEntities as $configurableBundleTemplateSlotEntity) {
            $configurableBundleTemplateSlotEntity->delete();
        }
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function activateConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void
    {
        $configurableBundleTemplateEntity = $this->getFactory()
            ->createConfigurableBundleTemplateQuery()
            ->filterByIdConfigurableBundleTemplate($idConfigurableBundleTemplate)
            ->findOne();

        if (!$configurableBundleTemplateEntity) {
            return;
        }

        $configurableBundleTemplateEntity
            ->setIsActive(true)
            ->save();
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function deactivateConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void
    {
        $configurableBundleTemplateEntity = $this->getFactory()
            ->createConfigurableBundleTemplateQuery()
            ->findOneByIdConfigurableBundleTemplate($idConfigurableBundleTemplate);

        if (!$configurableBundleTemplateEntity) {
            return;
        }

        $configurableBundleTemplateEntity->setIsActive(false)
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    public function createConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotEntity = $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapConfigurableBundleTemplateSlotTransferToEntity($configurableBundleTemplateSlotTransfer, new SpyConfigurableBundleTemplateSlot());

        $configurableBundleTemplateSlotEntity->save();
        $configurableBundleTemplateSlotTransfer->setIdConfigurableBundleTemplateSlot(
            $configurableBundleTemplateSlotEntity->getIdConfigurableBundleTemplateSlot()
        );

        return $configurableBundleTemplateSlotTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return bool
     */
    public function updateConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): bool {
        $configurableBundleTemplateSlotEntity = $this->getFactory()
            ->createConfigurableBundleTemplateSlotQuery()
            ->findOneByIdConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot());

        if (!$configurableBundleTemplateSlotEntity) {
            return false;
        }

        $configurableBundleTemplateSlotEntity = $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapConfigurableBundleTemplateSlotTransferToEntity($configurableBundleTemplateSlotTransfer, $configurableBundleTemplateSlotEntity);

        $configurableBundleTemplateSlotEntity->save();

        return true;
    }

    /**
     * @param int $idConfigurableBundleTemplateSlot
     *
     * @return void
     */
    public function deleteConfigurableBundleTemplateSlotById(int $idConfigurableBundleTemplateSlot): void
    {
        $configurableBundleTemplateSlotEntity = $this->getFactory()
            ->createConfigurableBundleTemplateSlotQuery()
            ->filterByIdConfigurableBundleTemplateSlot($idConfigurableBundleTemplateSlot)
            ->findOne();

        if (!$configurableBundleTemplateSlotEntity) {
            return;
        }

        $configurableBundleTemplateSlotEntity->delete();
    }
}
