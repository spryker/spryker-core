<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotGui\Communication;

use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplateQuery;
use Spryker\Zed\CmsSlotGui\CmsSlotGuiDependencyProvider;
use Spryker\Zed\CmsSlotGui\Communication\Dependency\CmsSlotGuiToCmsSlotFacadeInterface;
use Spryker\Zed\CmsSlotGui\Communication\Table\SlotTable;
use Spryker\Zed\CmsSlotGui\Communication\Table\TemplateTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CmsSlotGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CmsSlotGui\Communication\Table\TemplateTable
     */
    public function createTemplateListTable(): TemplateTable
    {
        return new TemplateTable(
            $this->getCmsSlotTemplateQuery()
        );
    }

    /**
     * @param int|null $idSlotTemplate
     *
     * @return \Spryker\Zed\CmsSlotGui\Communication\Table\SlotTable
     */
    public function createSlotListTable(?int $idSlotTemplate = null): SlotTable
    {
        return new SlotTable(
            $this->getCmsSlotQuery(),
            $idSlotTemplate
        );
    }

    /**
     * @return \Spryker\Zed\CmsSlotGui\Communication\Dependency\CmsSlotGuiToCmsSlotFacadeInterface
     */
    public function getCmsSlotFacade(): CmsSlotGuiToCmsSlotFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotGuiDependencyProvider::FACADE_CMS_SLOT);
    }

    /**
     * @return \Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplateQuery
     */
    public function getCmsSlotTemplateQuery(): SpyCmsSlotTemplateQuery
    {
        return $this->getProvidedDependency(CmsSlotGuiDependencyProvider::PROPER_QUERY_CMS_SLOT_TEMPLATE);
    }

    /**
     * @return \Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery
     */
    public function getCmsSlotQuery(): SpyCmsSlotQuery
    {
        return $this->getProvidedDependency(CmsSlotGuiDependencyProvider::PROPER_QUERY_CMS_SLOT);
    }
}
