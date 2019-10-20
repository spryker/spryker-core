<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication;

use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Spryker\Zed\CmsSlotBlockGui\CmsSlotBlockGuiDependencyProvider;
use Spryker\Zed\CmsSlotBlockGui\Communication\Form\DataProvider\CmsBlockChoiceFormDataProvider;
use Spryker\Zed\CmsSlotBlockGui\Communication\Form\DataProvider\CmsBlockChoiceFormDataProviderInterface;
use Spryker\Zed\CmsSlotBlockGui\Communication\Form\DataProvider\SlotBlockCollectionDataProvider;
use Spryker\Zed\CmsSlotBlockGui\Communication\Form\DataProvider\SlotBlockCollectionDataProviderInterface;
use Spryker\Zed\CmsSlotBlockGui\Communication\Form\SlotBlock\CmsBlockChoiceForm;
use Spryker\Zed\CmsSlotBlockGui\Communication\Form\SlotBlock\SlotBlockCollectionForm;
use Spryker\Zed\CmsSlotBlockGui\Communication\Table\CmsSlotBlockTable;
use Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsBlockFacadeInterface;
use Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface;
use Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\CmsSlotBlockGui\CmsSlotBlockGuiConfig getConfig()
 */
class CmsSlotBlockGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return \Spryker\Zed\CmsSlotBlockGui\Communication\Table\CmsSlotBlockTable
     */
    public function createSlotBlockTable(int $idCmsSlotTemplate, int $idCmsSlot): CmsSlotBlockTable
    {
        return new CmsSlotBlockTable(
            $this->getCmsBlockPropelQuery(),
            $idCmsSlotTemplate,
            $idCmsSlot,
            $this->getCmsBlockFacade()
        );
    }

    /**
     * @param \Spryker\Zed\CmsSlotBlockGui\Communication\Form\DataProvider\SlotBlockCollectionDataProviderInterface $slotBlockDataProvider
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createSlotBlockCollectionForm(
        SlotBlockCollectionDataProviderInterface $slotBlockDataProvider,
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
     * @param \Spryker\Zed\CmsSlotBlockGui\Communication\Form\DataProvider\CmsBlockChoiceFormDataProviderInterface $choiceFormDataProvider
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCmsBlockChoiceForm(
        CmsBlockChoiceFormDataProviderInterface $choiceFormDataProvider,
        int $idCmsSlotTemplate,
        int $idCmsSlot
    ): FormInterface {
        return $this->getFormFactory()->create(
            CmsBlockChoiceForm::class,
            null,
            $choiceFormDataProvider->getOptions($idCmsSlotTemplate, $idCmsSlot)
        );
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockGui\Communication\Form\DataProvider\SlotBlockCollectionDataProviderInterface
     */
    public function createSlotBlockCollectionDataProvider(): SlotBlockCollectionDataProviderInterface
    {
        return new SlotBlockCollectionDataProvider($this->getCmsSlotBlockFacade());
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockGui\Communication\Form\DataProvider\CmsBlockChoiceFormDataProviderInterface
     */
    public function createCmsBlockChoiceFormDataProvider(): CmsBlockChoiceFormDataProviderInterface
    {
        return new CmsBlockChoiceFormDataProvider(
            $this->getCmsSlotBlockFacade()
        );
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

    /**
     * @return \Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsBlockFacadeInterface
     */
    public function getCmsBlockFacade(): CmsSlotBlockGuiToCmsBlockFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockGuiDependencyProvider::FACADE_CMS_BLOCK);
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function getCmsBlockPropelQuery(): SpyCmsBlockQuery
    {
        return $this->getProvidedDependency(CmsSlotBlockGuiDependencyProvider::PROPEL_QUERY_CMS_BLOCK);
    }
}
