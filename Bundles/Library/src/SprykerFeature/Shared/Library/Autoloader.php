<?php
namespace SprykerFeature\Shared\Library;

class Autoloader
{

    /**
     * @var array
     */
    private static $allowedNamespaces = [
        'SprykerFeature',
        'Generated'
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
     * @param string $rootDirectory
     * @param string $vendorDirectory
     * @param string $application
     */
    private function __construct($rootDirectory, $vendorDirectory, $application = null)
    {
        $this->application = $application;
        $this->rootDirectory = $rootDirectory;

        require_once $vendorDirectory . '/autoload.php';
    }

    /**
     * @param string $rootDirectory
     * @param string $vendorDirectory
     * @param string $application
     * @static
     */
    public static function register($rootDirectory, $vendorDirectory, $application = null)
    {
        if (!self::$instance) {
            self::$instance = new self($rootDirectory, $vendorDirectory, $application);
            spl_autoload_register([self::$instance, 'autoload'], true, true);
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
     * @param string $resourceName
     * @return string Resource relative path.
     */
    private function getResourceRelativePath($resourceName)
    {
        $resourcePath = '';

        if (($lastNamespacePosition = strrpos($resourceName, '\\')) !== false) {
            // Namespaced resource name
            $resourceNamespace = substr($resourceName, 0, $lastNamespacePosition);
            $resourceName = substr($resourceName, $lastNamespacePosition + 1);
            $resourcePath = str_replace('\\', DIRECTORY_SEPARATOR, $resourceNamespace) . DIRECTORY_SEPARATOR;
        }

        return $resourcePath . str_replace('_', DIRECTORY_SEPARATOR, $resourceName) . '.php';
    }

    /**
     * Transform relative path into its absolute resource path representation.
     *
     * @param string $relativePath
     * @return string|null Resource relative path.
     */
    private function getResourceAbsolutePath($relativePath)
    {
        $absolutePath = $this->rootDirectory . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $relativePath;
        if (file_exists($absolutePath)) {
            return $absolutePath;
        }
        return null;
    }

    /**
     * Try to load a Yves or Zed class
     * with fallback to composer
     *
     * @param string $resourceName
     * @return bool
     */
    protected function autoload($resourceName)
    {
        // We always work with FQCN in our context
        // php bug from 5.3.0 to 5.3.2
        $resourceName = ltrim($resourceName, '\\');
        if ($this->isLoadingAllowed($resourceName)) {
            $this->checkApplication($resourceName);

            // Classes in Core Namespace like "SprykerFeature" are loaded through composer
            foreach (self::$allowedNamespaces as $ns) {
                if (strpos($resourceName, $ns) === 0 && $ns !== 'Generated') {
                    return false;
                }
            }

            $relativePath = $this->getResourceRelativePath($resourceName);
            $absolutePath = $this->getResourceAbsolutePath($relativePath);
            if ($absolutePath) {
                require_once $absolutePath;
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $resourceName
     * @return bool
     */
    private function isLoadingAllowed($resourceName)
    {
        foreach (self::$allowedNamespaces as $ns) {
            // due to the fact that we could have a namespace like "Pro" and a resource name "Propel"
            // therefore after the namespace we have either a  "\" or a "_"
            if ((strpos($resourceName, $ns . '\\') === 0) || (strpos($resourceName, $ns . '_') === 0)) {
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
        self::$allowedNamespaces[] = $namespace;
    }

    /**
     * Checks if the class is allowed inside Yves or Zed
     *
     * @param $resourceName
     * @throws \Exception
     */
    protected function checkApplication($resourceName)
    {
        if (!$this->application) {
            return;
        }
        // Check if it is a namespace class
        if (strpos($resourceName, '\\') !== false) {
            $classPieces = explode('\\', $resourceName);
        } else {
            $classPieces = explode('_', $resourceName);
        }

        $app = ucfirst(strtolower($this->application));
        if (($classPieces[1] !== $app && ($classPieces[1] === 'Yves' || $classPieces[1] === 'Zed')) && $classPieces[1] !== 'Shared') {
            throw new \Exception('You are not allowed to load this class in your app. (' . $resourceName . ')');
        }
    }

}
