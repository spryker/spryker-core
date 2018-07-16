<?php

namespace Spryker\Service\UtilUuidGenerator;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilUuidGenerator\UtilUuidGeneratorServiceFactory getFactory()
 */
class UtilUuidGeneratorService extends AbstractService implements UtilUuidGeneratorServiceInterface
{
    /**
     * @param string $name
     *
     * @return string
     */
    public function generateUuid5(string $name): string
    {
        $uuidGenerator = $this->getFactory()->getUuid5Generator();

        return $uuidGenerator->uuid5($name);
    }
}
