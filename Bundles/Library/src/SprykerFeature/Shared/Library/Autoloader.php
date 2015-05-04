<?php
namespace SprykerFeature\Shared\Library;

class Autoloader
{

    /**
     * @var array
     */
    private static $allowedNamespaces = [
        'SprykerFeature',
        'SprykerEngine',
        'Functional',
        'Acceptance',
        'Unit',
        'YvesUnit',
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
     * @var bool
     */
    private $disableApplicationCheck;

    /**
     * @param $rootDirectory
     * @param $vendorDirectory
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
     * @param $rootDirectory
     * @param $vendorDirectory
     * @param null $application
     * @param bool $disableApplicationCheck
     */
    public static function register($rootDirectory, $vendorDirectory, $application = null, $disableApplicationCheck = false)
    {
        if (!self::$instance) {
            self::$instance = new self($rootDirectory, $vendorDirectory, $application, $disableApplicationCheck);
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
        //manual namespacing
        $resourcePath = '%dir%\\' . str_replace('_', '\\', $resourceName);

        $testDirs = ['Functional', 'Unit', 'Acceptance'];
        $testDirSearch = array_map(function($value) {
            return '%dir%\\' . $value;
        }, $testDirs);

        $testDirReplace = array_map(function($value) {
            return 'tests\\' . $value;
        }, $testDirs);

        $resourcePath = str_replace($testDirSearch, $testDirReplace, $resourcePath);
        $resourcePath = str_replace('%dir%', 'src\\%remove%', $resourcePath);

        $resourceParts = explode('\\', $resourcePath);
        $bundle = $resourceParts[4];

        $resourcePath = str_replace(DIRECTORY_SEPARATOR . '%remove%', '', implode(DIRECTORY_SEPARATOR, $resourceParts));

        //'SprykerFeature\Shared\Library\Filter';
        //'Bundles/Library/src/SprykerFeature/Shared/Library/Filter'

        //'Functional\SprykerFeature\Shared\Library\Filter
        //'Bundles/Library/tests/Functional/SprykerFeature/Shared/Library/Filter'

        //\%dir%\Functional \SprykerFeature\Shared\Library\Filter
        //\tests\Functional \SprykerFeature\Shared\Library\Filter

        //\%dir%\SprykerFeature\Shared\Library\Filter
        //\src\%remove%\SprykerFeature\Shared\Library\Filter

        $relativeResourcePath = implode(DIRECTORY_SEPARATOR, ['Bundles', $bundle, $resourcePath]);

        return $relativeResourcePath . '.php';
    }

    /**
     * Transform relative path into its absolute resource path representation.
     *
     * @param string $relativePath
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
     * @return bool
     */
    protected function autoload($resourceName)
    {
        // We always work with FQCN in our context
        // php bug from 5.3.0 to 5.3.2
        $resourceName = ltrim($resourceName, '\\');
        if ($this->isLoadingAllowed($resourceName)) {
            if (!$this->disableApplicationCheck) {
                $this->checkApplication($resourceName);
            }

            $relativePath = $this->getResourceRelativePath($resourceName);
            $absolutePath = $this->getResourceAbsolutePath($relativePath);

            if (file_exists($absolutePath)) {
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
