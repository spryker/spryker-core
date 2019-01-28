<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication;

use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Zed\ContentGui\Communication\Table\ContentTable;
use Spryker\Zed\ContentGui\ContentGuiDependencyProvider;
use Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class ContentGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ContentGui\Communication\Table\ContentTable
     */
    public function createContentTable(): ContentTable
    {
        return new ContentTable(
            $this->getPropelContentQuery(),
            $this->getUtilDateTimeService()
        );
    }

    /**
     * @return \Orm\Zed\Content\Persistence\SpyContentQuery
     */
    protected function getPropelContentQuery(): SpyContentQuery
    {
        return $this->getProvidedDependency(ContentGuiDependencyProvider::PROPEL_QUERY_CONTENT);
    }

    /**
     * @return \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToUtilDateTimeServiceInterface
     */
    protected function getUtilDateTimeService(): ContentGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(ContentGuiDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }
}
