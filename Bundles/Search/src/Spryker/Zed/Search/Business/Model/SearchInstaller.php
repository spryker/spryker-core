<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model;

class SearchInstaller implements SearchInstallerInterface
{
    /**
     * @var \Spryker\Zed\SearchExtension\Dependency\Plugin\InstallPluginInterface[]|\Spryker\Zed\Search\Business\Model\SearchInstallerInterface[] (deprecated Use `Spryker\Zed\SearchExtension\Dependency\Plugin\InstallPluginInterface` instead)
     */
    protected $installer = [];

    /**
     * @param \Spryker\Zed\SearchExtension\Dependency\Plugin\InstallPluginInterface[]|\Spryker\Zed\Search\Business\Model\SearchInstallerInterface[] $installer (deprecated Use `Spryker\Zed\SearchExtension\Dependency\Plugin\InstallPluginInterface` instead)
     */
    public function __construct(array $installer)
    {
        $this->installer = $installer;
    }

    /**
     * @return void
     */
    public function install(/* LoggerInterface $logger */)
    {
        $arguments = func_get_args();
        $logger = null;
        if (count($arguments) === 1) {
            $logger = current($arguments);
        }

        foreach ($this->installer as $installer) {
            if ($installer instanceof SearchInstallerInterface) {
                $installer->install();
                continue;
            }

            $installer->installIndices($logger);
            $installer->installMapper($logger);
        }
    }
}
