
<?php

namespace Spryker\Service\UtilUuidGenerator;

interface UtilUuidGeneratorServiceInterface
{
    /**
     * @param string $name
     *
     * @return string
     */
    public function generateUuid5(string $name): string;
}
