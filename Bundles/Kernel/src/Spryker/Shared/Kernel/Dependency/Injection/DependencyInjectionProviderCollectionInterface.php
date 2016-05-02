<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Dependency\Injection;

interface DependencyInjectionProviderCollectionInterface extends \Countable
{

    /**
     * @param \Spryker\Shared\Kernel\Dependency\Injection\DependencyInjectionInterface $dependencyInjectorProvider
     *
     * @return $this
     */
    public function addDependencyInjectorProvider(DependencyInjectionInterface $dependencyInjectorProvider);

    /**
     * @return \Spryker\Shared\Kernel\Dependency\Injection\DependencyInjectionInterface[]
     */
    public function getDependencyInjectionProvider();

}
