<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotGui\Communication;

use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplateQuery;
use Spryker\Zed\CmsSlotGui\CmsSlotGuiDependencyProvider;
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
     * @return \Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplateQuery
     */
    protected function getCmsSlotTemplateQuery(): SpyCmsSlotTemplateQuery
    {
        return $this->getProvidedDependency(CmsSlotGuiDependencyProvider::PROPER_QUERY_CMS_SLOT_TEMPLATE);
    }
}
