<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library;

class TestAutoloader
{

    /**
     * @var array
     */
    private static $allowedNamespaces = [
        'Unit',
        'YvesUnit',
        'Functional',
        'Acceptance',
    ];

    /**
     * @var Autoloader
     */
    private static $instance;

    /**
     * @var string
     */
    private $application;

    /**
     * @var bool
     */
    private $disableApplicationCheck;

    /**
     * @var array
     */
    private $classMap = [];

    /**
     * @param string $rootDirectory
     * @param string $vendorDirectory
     * @param null $application
     * @param bool $disableApplicationCheck
     */
    private function __construct($rootDirectory, $vendorDirectory, $application = null, $disableApplicationCheck = false)
    {
        $this->application = $application;
        $this->rootDirectory = $rootDirectory;
        $this->disableApplicationCheck = $disableApplicationCheck;

        require_once $vendorDirectory . '/autoload.php';
    }

    /**
     * @param string $rootDirectory
     * @param string $vendorDirectory
     * @param null $application
     * @param bool $disableApplicationCheck
     */
    public static function register($rootDirectory, $vendorDirectory, $application = null, $disableApplicationCheck = false)
    {
        if (!self::$instance) {
            self::$instance = new self($rootDirectory, $vendorDirectory, $application, $disableApplicationCheck);
            spl_autoload_register([self::$instance, 'autoload'], true, false);
        }
    }

    /**
     * @return Autoloader
     */
    public static function unregister()
    {
        if (self::$instance) {
            spl_autoload_unregister([self::$instance, 'autoload']);
            self::$instance = null;
        }
    }

    /**
     * Transform resource name into its relative resource path representation.
     *
     * @param string $resourceParts
     *
     * @return string Resource relative path.
     */
    private function getResourceRelativePath($resourceParts)
    {
        $bundle = $resourceParts[3];
        $relativeResourcePath = 'Bundles/' . $bundle . '/tests/' . implode(DIRECTORY_SEPARATOR, $resourceParts);

        return $relativeResourcePath . '.php';
    }

    /**
     * Transform relative path into its absolute resource path representation.
     *
     * @param string $relativePath
     *
     * @return string|null Resource relative path.
     */
    private function getResourceAbsolutePath($relativePath)
    {
        $absolutePath = $this->rootDirectory . DIRECTORY_SEPARATOR . $relativePath;

        return $absolutePath;
    }

    /**
     * Try to load a Yves or Zed class
     * with fallback to composer
     *
     * @param string $resourceName
     *
     * @return bool
     */
    protected function autoload($resourceName)
    {
        $file = $this->findFile($resourceName);
        if ($file !== false) {
            include $file;
        } else {
            $this->classMap[$resourceName] = false;

            return false;
        }
    }

    protected function findFile($resourceName)
    {
        // We always work with FQCN in our context
        // php bug from 5.3.0 to 5.3.2
        $resourceName = ltrim($resourceName, '\\');

        if (isset($this->classMap[$resourceName])) {
            return $this->classMap[$resourceName];
        }

        if (!$this->isLoadingAllowed($resourceName)) {
            return false;
        }

        $resourceName = str_replace('_', '\\', $resourceName);
        $resourceParts = explode('\\', $resourceName);

        if (!$this->disableApplicationCheck) {
            $this->checkApplication($resourceParts);
        }

        $relativePath = $this->getResourceRelativePath($resourceParts);
        $absolutePath = $this->getResourceAbsolutePath($relativePath);

        if (file_exists($absolutePath)) {
            return $absolutePath;
        }

        return false;
    }

    /**
     * @param string $resourceName
     *
     * @return bool
     */
    private function isLoadingAllowed($resourceName)
    {
        foreach (self::$allowedNamespaces as $ns) {
            $namespaceLength = strlen($ns);
            if (substr($resourceName, 0, $namespaceLength) !== $ns) {
                continue;
            }
            if ($resourceName[$namespaceLength] === '_' || $resourceName[$namespaceLength] === '\\') {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $namespace
     */
    public static function allowNamespace($namespace)
    {
        if (!in_array($namespace, self::$allowedNamespaces)) {
            self::$allowedNamespaces[] = $namespace;
        }
    }

    /**
     * Checks if the class is allowed inside Yves or Zed
     *
     * @param array $resourceParts
     *
     * @throws \Exception
     */
    protected function checkApplication($resourceParts)
    {
        if (!$this->application) {
            return;
        }

        $app = ucfirst(strtolower($this->application));
        if (($resourceParts[1] !== $app && ($resourceParts[1] === 'Yves' || $resourceParts[1] === 'Zed')) && $resourceParts[1] !== 'Shared') {
            throw new \Exception('You are not allowed to load this class in your app. (' . implode('\\', $resourceParts) . ')');
        }
    }

}
