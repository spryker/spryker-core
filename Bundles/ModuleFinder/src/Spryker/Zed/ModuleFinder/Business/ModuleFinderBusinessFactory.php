<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ModuleFinder\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ModuleFinder\Business\Module\ModuleFinder\ModuleFinder;
use Spryker\Zed\ModuleFinder\Business\Module\ModuleFinder\ModuleFinderInterface;
use Spryker\Zed\ModuleFinder\Business\Module\ModuleMatcher\ModuleMatcher;
use Spryker\Zed\ModuleFinder\Business\Module\ModuleMatcher\ModuleMatcherInterface;
use Spryker\Zed\ModuleFinder\Business\Module\ProjectModuleFinder\ProjectModuleFinder;
use Spryker\Zed\ModuleFinder\Business\Module\ProjectModuleFinder\ProjectModuleFinderInterface;
use Spryker\Zed\ModuleFinder\Business\Package\PackageFinder\PackageFinder;
use Spryker\Zed\ModuleFinder\Business\Package\PackageFinder\PackageFinderInterface;

/**
 * @method \Spryker\Zed\ModuleFinder\ModuleFinderConfig getConfig()
 * @method \Spryker\Zed\ModuleFinder\Persistence\ModuleFinderEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ModuleFinder\Persistence\ModuleFinderRepositoryInterface getRepository()
 */
class ModuleFinderBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ModuleFinder\Business\Module\ModuleFinder\ModuleFinderInterface
     */
    public function createModuleFinder(): ModuleFinderInterface
    {
        return new ModuleFinder($this->getConfig(), $this->createModuleMatcher());
    }

    /**
     * @return \Spryker\Zed\ModuleFinder\Business\Module\ModuleMatcher\ModuleMatcherInterface
     */
    public function createModuleMatcher(): ModuleMatcherInterface
    {
        return new ModuleMatcher();
    }

    /**
     * @return \Spryker\Zed\ModuleFinder\Business\Module\ProjectModuleFinder\ProjectModuleFinderInterface
     */
    public function createProjectModuleFinder(): ProjectModuleFinderInterface
    {
        return new ProjectModuleFinder($this->getConfig(), $this->createModuleMatcher());
    }

    /**
     * @return \Spryker\Zed\ModuleFinder\Business\Package\PackageFinder\PackageFinderInterface
     */
    public function createPackageFinder(): PackageFinderInterface
    {
        return new PackageFinder($this->getConfig());
    }
}
