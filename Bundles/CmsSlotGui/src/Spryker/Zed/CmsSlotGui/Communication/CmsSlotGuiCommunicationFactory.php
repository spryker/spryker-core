<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotGui\Communication;

use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplateQuery;
use Spryker\Zed\CmsSlotGui\CmsSlotGuiDependencyProvider;
use Spryker\Zed\CmsSlotGui\Communication\Table\SlotListTable;
use Spryker\Zed\CmsSlotGui\Communication\Table\TemplateListTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CmsSlotGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CmsSlotGui\Communication\Table\TemplateListTable
     */
    public function createTemplateListTable(): TemplateListTable
    {
        return new TemplateListTable(
            $this->getCmsSlotTemplateQuery()
        );
    }

    /**
     * @param int|null $idSlotTemplate
     *
     * @return \Spryker\Zed\CmsSlotGui\Communication\Table\SlotListTable
     */
    public function createSlotListTable(?int $idSlotTemplate = null): SlotListTable
    {
        return new SlotListTable(
            $this->getCmsSlotQuery(),
            $idSlotTemplate
        );
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
