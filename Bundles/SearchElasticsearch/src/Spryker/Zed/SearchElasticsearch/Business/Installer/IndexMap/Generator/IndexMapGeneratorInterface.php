<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Generator;

use Generated\Shared\Transfer\IndexDefinitionTransfer;

interface IndexMapGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\IndexDefinitionTransfer $indexDefinition
     *
     * @return void
     */
    public function generate(IndexDefinitionTransfer $indexDefinition): void;
}
