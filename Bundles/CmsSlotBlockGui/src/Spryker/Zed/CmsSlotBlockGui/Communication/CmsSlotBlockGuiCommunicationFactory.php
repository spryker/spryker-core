<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication;

use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlockQuery;
use Spryker\Zed\CmsSlotBlockGui\CmsSlotBlockGuiDependencyProvider;
use Spryker\Zed\CmsSlotBlockGui\Communication\Form\DataProvider\SlotBlockCollectionDataProvider;
use Spryker\Zed\CmsSlotBlockGui\Communication\Form\DataProvider\SlotBlockDataProviderInterface;
use Spryker\Zed\CmsSlotBlockGui\Communication\Form\SlotBlock\SlotBlockCollectionForm;
use Spryker\Zed\CmsSlotBlockGui\Communication\Table\CmsSlotBlockTable;
use Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface;
use Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

class CmsSlotBlockGuiCommunicationFactory extends AbstractCommunicationFactory
{
    public function createSlotBlockTable(): CmsSlotBlockTable
    {
        return new CmsSlotBlockTable(new SpyCmsBlockQuery(), 1);
    }

    /**
     * @param \Spryker\Zed\CmsSlotBlockGui\Communication\Form\DataProvider\SlotBlockDataProviderInterface $slotBlockDataProvider
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createSlotBlockCollectionForm(
        SlotBlockDataProviderInterface $slotBlockDataProvider,
        int $idCmsSlotTemplate,
        int $idCmsSlot
    ): FormInterface {
        return $this->getFormFactory()->create(
            SlotBlockCollectionForm::class,
            $slotBlockDataProvider->getData($idCmsSlotTemplate, $idCmsSlot),
            $slotBlockDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockGui\Communication\Form\DataProvider\SlotBlockDataProviderInterface
     */
    public function createSlotBlockCollectionDataProvider(): SlotBlockDataProviderInterface
    {
        return new SlotBlockCollectionDataProvider($this->getCmsSlotBlockFacade());
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface
     */
    public function getCmsSlotBlockFacade(): CmsSlotBlockGuiToCmsSlotBlockFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockGuiDependencyProvider::FACADE_CMS_SLOT_BLOCK);
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotFacadeInterface
     */
    public function getCmsSlotFacade(): CmsSlotBlockGuiToCmsSlotFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockGuiDependencyProvider::FACADE_CMS_SLOT);
    }
}
