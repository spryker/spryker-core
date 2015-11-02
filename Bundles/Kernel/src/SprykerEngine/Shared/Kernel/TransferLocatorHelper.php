<?php

namespace SprykerEngine\Shared\Kernel;

use SprykerFeature\Shared\Library\Config;
use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerFeature\Shared\System\SystemConfig;

class TransferLocatorHelper
{

    /**
     * @param LocatorLocatorInterface $locator
     * @param string $className
     *
     * @throws \Exception
     *
     * @return TransferInterface
     */
    public function createTransferFromClassName(LocatorLocatorInterface $locator, $className)
    {
        $className = str_replace($this->getNamespaces(), '', $className, $count);
        if ($count !== 3) {
            throw new \Exception(sprintf('TransferClass %s  has the wrong namespace.', $className));
        }
        $segments = explode('\\', $className);
        $bundleName = array_shift($segments);
        $getMethodName = 'transfer' . implode('', $segments);

        return $locator->$bundleName()->$getMethodName();
    }

    /**
     * @TODO replace this with configured namespaces
     *
     * @throws \Exception
     *
     * @return array
     */
    private function getNamespaces()
    {
        $projectNamespace = Config::get(SystemConfig::PROJECT_NAMESPACE);
        $namespaces = [
            $projectNamespace . '\\',
            'SprykerFeature\\',
            'SprykerEngine\\',
            'Shared\\',
            'Transfer\\',
        ];

        return $namespaces;
    }

}
