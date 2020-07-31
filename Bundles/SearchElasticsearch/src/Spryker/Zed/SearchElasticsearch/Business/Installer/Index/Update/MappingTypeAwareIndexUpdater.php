<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Update;

use Elastica\Index;

class MappingTypeAwareIndexUpdater extends AbstractIndexUpdater
{
    /**
     * @param array $mappings
     * @param \Elastica\Index $index
     *
     * @return void
     */
    protected function buildMapping(array $mappings, Index $index): void
    {
        /** @var \Elastica\Type\Mapping $mapping */
        $mapping = $this->mappingBuilder->buildMapping($mappings, $index);
        $mapping->send();
    }
}
