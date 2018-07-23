<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilUuidGenerator;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilUuidGenerator\Dependency\External\UtilUuidGeneratorToUuid5GeneratorInterface;

class UtilUuidGeneratorServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilUuidGenerator\Dependency\External\UtilUuidGeneratorToUuid5GeneratorInterface
     */
    public function getUuid5Generator(): UtilUuidGeneratorToUuid5GeneratorInterface
    {
        return $this->getProvidedDependency(UtilUuidGeneratorDependencyProvider::UUID5_GENERATOR);
    }
}
