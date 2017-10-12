<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Generator;

use Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer;

interface IndexMapGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer $indexDefinition
     *
     * @return void
     */
    public function generate(ElasticsearchIndexDefinitionTransfer $indexDefinition);
}
