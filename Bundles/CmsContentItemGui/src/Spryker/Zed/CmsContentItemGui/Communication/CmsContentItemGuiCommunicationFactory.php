<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentItemGui\Communication;

use Orm\Zed\CmsContentPlan\Persistence\Base\SpyCmsContentPlanQuery;
use Spryker\Zed\CmsContentItemGui\CmsContentItemGuiDependencyProvider;
use Spryker\Zed\CmsContentItemGui\Communication\Table\CmsContentItemTable;
use Spryker\Zed\CmsContentItemGui\Dependency\Service\CmsContentItemGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CmsContentItemGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CmsContentItemGui\Communication\Table\CmsContentItemTable
     */
    public function createCmsContentItemTable(): CmsContentItemTable
    {
        return new CmsContentItemTable(
            $this->getPropelCmsContentPlanQuery(),
            $this->getUtilDateTimeService()
        );
    }

    /**
     * @return \Orm\Zed\CmsContentPlan\Persistence\Base\SpyCmsContentPlanQuery
     */
    protected function getPropelCmsContentPlanQuery(): SpyCmsContentPlanQuery
    {
        return $this->getProvidedDependency(CmsContentItemGuiDependencyProvider::PROPEL_CMS_CONTENT_PLAN);
    }

    /**
     * @return \Spryker\Zed\CmsContentItemGui\Dependency\Service\CmsContentItemGuiToUtilDateTimeServiceInterface
     */
    protected function getUtilDateTimeService(): CmsContentItemGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(CmsContentItemGuiDependencyProvider::UTIL_DATE_TIME_SERVICE);
    }
}
