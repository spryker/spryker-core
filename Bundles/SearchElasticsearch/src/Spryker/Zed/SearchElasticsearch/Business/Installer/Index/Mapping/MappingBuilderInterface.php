<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping;

use Elastica\Index;
use Elastica\Mapping;
use Generated\Shared\Transfer\IndexDefinitionTransfer;

interface MappingBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\IndexDefinitionTransfer $indexDefinitionTransfer
     * @param \Elastica\Index $index
     *
     * @return \Elastica\Mapping
     */
    public function buildMapping(IndexDefinitionTransfer $indexDefinitionTransfer, Index $index): Mapping;
}
