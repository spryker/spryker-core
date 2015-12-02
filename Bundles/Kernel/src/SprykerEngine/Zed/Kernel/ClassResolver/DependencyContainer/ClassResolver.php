<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerEngine\Zed\Kernel;

use SprykerEngine\Shared\Config;
use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Shared\System\SystemConfig;

class ClassResolver
{

    const KEY_NAMESPACE = 0;
    const KEY_APPLICATION = 1;
    const KEY_BUNDLE = 2;
    const KEY_LAYER = 3;
    const NAMESPACE_SPRYKER_FEATURE = 'SprykerFeature';
    const NAMESPACE_SPRYKER_ENGINE = 'SprykerEngine';

    /**
     * @var array
     */
    private $allowedTypes = [
        'DependencyContainer' => '\\%s\\%s\\%s\\%s\\%sDependencyContainer',
    ];

    /**
     * @param string $type
     * @param object $callerClass
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function resolve($type, $callerClass)
    {
        $callerClass = get_class($callerClass);
        $this->validateType($type, $callerClass);
        $callerClassParts = explode('\\', $callerClass);

        $className = $this->getStoreClassName($type, $callerClassParts);
        if (class_exists($className)) {
            return new $className();
        }

        $className = $this->getProjectClassName($type, $callerClassParts);
        if (class_exists($className)) {
            return new $className();
        }

        $className = $this->getCoreFeatureClassName($type, $callerClassParts);
        if (class_exists($className)) {
            return new $className();
        }

        $className = $this->getCoreEngineClassName($type, $callerClassParts);
        if (class_exists($className)) {
            return new $className();
        }

        throw new \Exception(sprintf(
            'This is a Spryker Kernel error.' . PHP_EOL . 'Could not resolve type "%s". This is called in "%s"',
            $type, $callerClass
        ));
    }

    /**
     * @param string $type
     * @param string $callerClass
     *
     * @throws \Exception
     *
     * @return void
     */
    private function validateType($type, $callerClass)
    {
        if (!array_key_exists($type, $this->allowedTypes)) {
            throw new \Exception(sprintf(
                'This is a Spryker Kernel error. ' . PHP_EOL . 'Type "%s "is not valid for class resolving. "%s" are allowed types.' . PHP_EOL . 'The calling class is "%s"',
                $type,
                implode(', ', array_keys($this->allowedTypes)),
                $callerClass
            ));
        }
    }

    /**
     * @param string $type
     * @param array $callerClassParts
     *
     * @return string
     */
    private function getStoreClassName($type, array $callerClassParts)
    {
        $store = Store::getInstance()->getStoreName();

        return sprintf(
            $this->allowedTypes[$type],
            $this->getProjectNamespace(),
            $this->getApplicationFromCallerClass($callerClassParts),
            $this->getBundleFromCallerClass($callerClassParts) . $store,
            $this->getLayerFromCallerClass($callerClassParts),
            $this->getBundleFromCallerClass($callerClassParts)
        );
    }

    /**
     * @param string $type
     * @param array $callerClassParts
     *
     * @return string
     */
    private function getProjectClassName($type, array $callerClassParts)
    {
        return sprintf(
            $this->allowedTypes[$type],
            $this->getProjectNamespace(),
            $this->getApplicationFromCallerClass($callerClassParts),
            $this->getBundleFromCallerClass($callerClassParts),
            $this->getLayerFromCallerClass($callerClassParts),
            $this->getBundleFromCallerClass($callerClassParts)
        );
    }

    /**
     * @param string $type
     * @param array $callerClassParts
     *
     * @return string
     */
    private function getCoreFeatureClassName($type, array $callerClassParts)
    {
        return sprintf(
            $this->allowedTypes[$type],
            self::NAMESPACE_SPRYKER_FEATURE,
            $this->getApplicationFromCallerClass($callerClassParts),
            $this->getBundleFromCallerClass($callerClassParts),
            $this->getLayerFromCallerClass($callerClassParts),
            $this->getBundleFromCallerClass($callerClassParts)
        );
    }

    /**
     * @param string $type
     * @param array $callerClassParts
     *
     * @return string
     */
    private function getCoreEngineClassName($type, array $callerClassParts)
    {
        return sprintf(
            $this->allowedTypes[$type],
            self::NAMESPACE_SPRYKER_ENGINE,
            $this->getApplicationFromCallerClass($callerClassParts),
            $this->getBundleFromCallerClass($callerClassParts),
            $this->getLayerFromCallerClass($callerClassParts),
            $this->getBundleFromCallerClass($callerClassParts)
        );
    }

    /**
     * @throws \Exception
     *
     * @return string
     */
    private function getProjectNamespace()
    {
        return Config::getInstance()->get(SystemConfig::PROJECT_NAMESPACE);
    }

    /**
     * @param array $callerClassParts
     *
     * @return string
     */
    private function getNamespaceFromCallerClass(array $callerClassParts)
    {
        return $callerClassParts[self::KEY_NAMESPACE];
    }

    /**
     * @param array $callerClassParts
     *
     * @return string
     */
    private function getApplicationFromCallerClass(array $callerClassParts)
    {
        return $callerClassParts[self::KEY_APPLICATION];
    }

    /**
     * @param array $callerClassParts
     *
     * @return string
     */
    private function getBundleFromCallerClass(array $callerClassParts)
    {
        return $callerClassParts[self::KEY_BUNDLE];
    }

    /**
     * @param array $callerClassParts
     *
     * @return string
     */
    private function getLayerFromCallerClass(array $callerClassParts)
    {
        return $callerClassParts[self::KEY_LAYER];
    }

}
