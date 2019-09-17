<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate;
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
     * @return bool
     */
    public function updateConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): bool {
        $configurableBundleTemplateEntity = $this->getFactory()
            ->createConfigurableBundleTemplateQuery()
            ->findOneByIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        if (!$configurableBundleTemplateEntity) {
            return false;
        }

        $configurableBundleTemplateEntity = $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapConfigurableBundleTemplateTransferToEntity($configurableBundleTemplateTransfer, $configurableBundleTemplateEntity);

        $configurableBundleTemplateEntity->save();

        return true;
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
            ->findOneByIdConfigurableBundleTemplate($idConfigurableBundleTemplate);

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
    public function deleteConfigurableBundleTemplateSlotsByIdConfigurableBundleTemplate(int $idConfigurableBundleTemplate): void
    {
        $this->getFactory()
            ->createConfigurableBundleTemplateSlotQuery()
            ->filterByFkConfigurableBundleTemplate($idConfigurableBundleTemplate)
            ->delete();
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
            ->findOneByIdConfigurableBundleTemplate($idConfigurableBundleTemplate);

        if (!$configurableBundleTemplateEntity) {
            return;
        }

        $configurableBundleTemplateEntity->setIsActive(true)
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
}
