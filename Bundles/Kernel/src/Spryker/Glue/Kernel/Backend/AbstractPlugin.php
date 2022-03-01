<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\Backend;

use Spryker\Glue\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Glue\Kernel\FactoryResolverAwareTrait;
use Spryker\Zed\Kernel\Communication\FacadeResolverAwareTrait;
use Spryker\Zed\Kernel\EntityManagerResolverAwareTrait;
use Spryker\Zed\Kernel\QueryContainerResolverAwareTrait;
use Spryker\Zed\Kernel\RepositoryResolverAwareTrait;

abstract class AbstractPlugin
{
    use RepositoryResolverAwareTrait;
    use FactoryResolverAwareTrait;
    use FacadeResolverAwareTrait;
    use BundleConfigResolverAwareTrait;
    use QueryContainerResolverAwareTrait;
    use EntityManagerResolverAwareTrait;

    /**
     * @api
     *
     * @return string
     */
    public function getModuleName(): string
    {
        $calledClass = $this->getFactoryResolver()->setCallerClass($this);
        $classInfo = $calledClass->getClassInfo();

        return $classInfo->getModule();
    }
}
