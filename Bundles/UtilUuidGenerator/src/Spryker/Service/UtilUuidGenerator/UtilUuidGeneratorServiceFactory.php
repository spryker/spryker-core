<?php

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
