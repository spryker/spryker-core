<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Definition;

interface IndexDefinitionLoaderInterface
{

    /**
     * @return \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer[]
     */
    public function loadIndexDefinitions();

}
