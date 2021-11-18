<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use Symfony\Component\Console\Output\OutputInterface;

interface ExporterInterface
{
    /**
     * @param string $type
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Collector\Business\Model\BatchResultInterface
     */
    public function exportByType($type, LocaleTransfer $localeTransfer, OutputInterface $output);

    /**
     * @return array<\Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginInterface>
     */
    public function getCollectorPlugins();
}
