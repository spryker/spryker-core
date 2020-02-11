<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Reader\Search;

use Generated\Shared\Transfer\SearchCollectorConfigurationTransfer;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;

interface ConfigurableSearchReaderInterface extends ReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer $collectorConfigurationTransfer
     *
     * @return void
     */
    public function setSearchCollectorConfiguration(SearchCollectorConfigurationTransfer $collectorConfigurationTransfer);

    /**
     * @return \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer
     */
    public function getSearchCollectorConfiguration();
}
