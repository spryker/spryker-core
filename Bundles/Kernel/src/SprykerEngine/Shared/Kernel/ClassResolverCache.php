<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\KernelConfig;

class ClassResolverCache
{
    /**
     * @var string
     */
    protected static $fileName = 'map.php';
    /**
     * @var bool
     */
    protected $isCacheActive = false;
    /**
     * @var string
     */
    protected $applicationRootDir;
    /**
     * @var string
     */
    protected $applicationName;

    public function __construct()
    {
        $this->isCacheActive = Config::getInstance()->get(KernelConfig::CLASS_RESOLVER_CACHE_ENABLED);
        $this->applicationName = APPLICATION;
        $this->applicationRootDir = APPLICATION_ROOT_DIR;
    }

    /**
     * @return array
     */
    public function loadClassMap()
    {
        $path = $this->createCacheFilePath();
        if ($this->isCacheActive && file_exists($path)) {
            global $map;
            /*
             * the @ is needed because file_exists may return a wrong value because of opcode cache
             * see opcache.enable_file_override
             */
            @include $path;
            if (false === is_array($map)) {
                $map = [];
            }
        } else {
            $map = [];
        }
        return $map;
    }

    /**
     * @param $map
     */
    public function saveClassMap($map)
    {
        if ($this->isCacheActive) {
            file_put_contents($this->createCacheFilePath(),
                '<?php ' . PHP_EOL . '$map = ' . var_export($map, true) . ';'
            );
        }
    }

    /**
     * @return string
     */
    protected function createCacheFilePath()
    {
        if ($this->isCacheActive) {
            $storeName = Store::getInstance()->getStoreName();
            $dir = $this->applicationRootDir . '/data/' . $storeName . '/cache/' . $this->applicationName . '/resolver/';
            if (false === is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            return $dir . self::$fileName;
        }
    }
}