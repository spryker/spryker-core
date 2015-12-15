<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development\Business\Refactor\Plugin;

use Spryker\Shared\Kernel\ClassMapFactory;
use Spryker\Zed\Development\Business\Refactor\AbstractRefactor;
use Spryker\Zed\Development\Business\Refactor\RefactorException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

class RefactorPluginInstantiation extends AbstractRefactor
{

    const CLASS_PART_APPLICATION = 1;

    /**
     * @var array
     */
    protected $directories = [];

    /**
     * @var array
     */
    protected $map;

    /**
     * @param array $directories
     */
    public function __construct(array $directories)
    {
        $this->directories = $directories;
        $this->map = include_once APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . ClassMapFactory::CLASS_MAP_FILE_NAME;
    }

    /**
     * @throws RefactorException
     *
     * @return void
     */
    public function refactor()
    {
        $phpFiles = $this->getFiles($this->directories, '*.php');

        $filesystem = new Filesystem();

        foreach ($phpFiles as $file) {
            $replaced = 0;
            $content = $file->getContents();

            $pattern = '/\\$(?:\\w+\\s*->\\s*getLocator\\(\\)|\\w+\\s*->\\s*locator|locator)\\s*->\\s*(\\w+)\\(\\)\\s*->\\s*(plugin\\w+)\\(\\)(\\s*->\\s*)?/';
            if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $this->replacePluginLocatorUsage($file, $content, $match[1], $match[2], isset($match[3]));

                    $replaced++;
                }
            }

            if ($replaced > 0) {
                $filesystem->dumpFile($file->getPathname(), $content);
            }
        }
    }

    /**
     * @param SplFileInfo $file
     * @param string $content
     * @param string $bundle
     * @param string $suffix
     * @param bool $useParenthesis
     *
     * @return void
     */
    protected function replacePluginLocatorUsage(SplFileInfo $file, &$content, $bundle, $suffix, $useParenthesis)
    {
        $key = $this->generateClassMapKey($file, $bundle, null, $suffix);

        if (!array_key_exists($key, $this->map)) {
            $key = $this->generateClassMapKey($file, $bundle, 'Communication', $suffix);

            if (!array_key_exists($key, $this->map)) {
                throw new RefactorException(sprintf(
                    'Class not found in class map with key "%s" used in file %s',
                    $key,
                    $file->getRealPath()
                ));
            }
        }

        $replacement = 'new ' . $this->map[$key] . '()';
        if ($useParenthesis) {
            $replacement = '(' . $replacement . ')';
        }

        $replacePattern = '/\$(?:\w+\s*->\s*getLocator\(\)|\w+\s*->\s*locator|locator)\s*->\s*' . $bundle . '\(\)\s*->\s*(' . $suffix . ')\(\)/';
        $content = preg_replace($replacePattern, $replacement, $content);
    }

    /**
     * @param SplFileInfo $file
     * @param string $bundle
     * @param string $layer
     * @param string $suffix
     *
     * @return string
     */
    protected function generateClassMapKey(SplFileInfo $file, $bundle, $layer, $suffix)
    {
        $applicationName = $this->getApplicationName($file);

        $key = $applicationName . '|' . ucfirst($bundle) . '|' . $layer . '|' . ucfirst($suffix);

        return $key;
    }

    /**
     * @param SplFileInfo $file
     *
     * @return string
     */
    protected function getApplicationName(SplFileInfo $file)
    {
        $className = $this->getClassNameFromFileInfo($file);
        $classParts = explode('\\', $className);

        return $classParts[self::CLASS_PART_APPLICATION];
    }

}
