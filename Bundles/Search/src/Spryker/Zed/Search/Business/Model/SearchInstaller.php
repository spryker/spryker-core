<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model;

class SearchInstaller implements SearchInstallerInterface
{
    /**
     * @var \Spryker\Zed\Search\Business\Model\SearchInstallerInterface[]
     */
    protected $installerStack = [];

    /**
     * @param \Spryker\Zed\Search\Business\Model\SearchInstallerInterface[] $installerStack
     */
    public function __construct(array $installerStack)
    {
        $this->installerStack = $installerStack;
    }

    /**
     * @return void
     */
    public function install()
    {
        foreach ($this->installerStack as $installer) {
            $installer->install();
        }
    }
}
