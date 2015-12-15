<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development\Business\Refactor\SprykerNamespace;

use Spryker\Zed\Development\Business\Refactor\AbstractRefactor;
use Spryker\Zed\Development\Business\Refactor\RefactorException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class RefactorNamespaces extends AbstractRefactor
{

    /**
     * @var array
     */
    protected $directories = [];

    /**
     * @param array $directories
     */
    public function __construct(array $directories)
    {
        $this->directories = $directories;
    }

    /**
     * @throws RefactorException
     *
     * @return void
     */
    public function refactor()
    {
        $phpFiles = $this->getFiles($this->directories);

        $filesystem = new Filesystem();

        foreach ($phpFiles as $file) {
            $content = $file->getContents();

            $replacedContent = $this->renameNamespaces($content);

            if ($replacedContent !== $content) {
                $filesystem->dumpFile($file->getPathname(), $replacedContent);
            }
        }
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function renameNamespaces($content)
    {
        return preg_replace('/\b(Spryker' . 'Feature|Spryker' . 'Engine)\b/', 'Spryker', $content);
    }

}
