<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui;

use Spryker\Shared\SearchElasticsearchGui\SearchElasticsearchGuiConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SearchElasticsearchGuiConfig extends AbstractBundleConfig
{
    /**
     * @return int
     */
    public function getFullTextBoostedBoostingValue(): int
    {
        return $this->get(SearchElasticsearchGuiConstants::FULL_TEXT_BOOSTED_BOOSTING_VALUE);
    }
}
