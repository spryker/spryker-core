<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Development\Business\Refactor\Client;

use SprykerFeature\Zed\Development\Business\Refactor\AbstractRefactor;
use SprykerFeature\Zed\Development\Business\Refactor\RefactorException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class RemoveServiceLayer extends AbstractRefactor
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
            if ($file->getExtension() === 'php') {
                $content = $file->getContents();

                $replacedContent = $this->removeServiceNamespace($content);
                $replacedContent = $this->fixDependencyContainer($replacedContent);

                if ($replacedContent !== $content) {
                    $filesystem->dumpFile($file->getPathname(), $replacedContent);
                }
            }

            $this->moveFileFromServiceNamespace($filesystem, $file);
        }
        $this->cleanupEmptyServiceFolders();
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function removeServiceNamespace($content)
    {
        return preg_replace('/(.*\\\\Client\\\\.*)\\\\Service\b/', '$1', $content);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function fixDependencyContainer($content)
    {
        return preg_replace('/AbstractServiceDependencyContainer/', 'AbstractDependencyContainer', $content);
    }

    /**
     * @param Filesystem $filesystem
     * @param SplFileInfo $phpFile
     *
     * @return void
     */
    protected function moveFileFromServiceNamespace(Filesystem $filesystem, SplFileInfo $phpFile)
    {
        if (preg_match('/(.*\/Client\/.*\/)Service\/(.*)/', $phpFile->getRealPath(), $matches)) {
            $targetFile = $matches[1] . $matches[2];

            $filesystem->copy($phpFile->getRealPath(), $targetFile);
            $filesystem->remove($phpFile->getRealPath());
        }
    }

    /**
     * @return void
     */
    protected function cleanupEmptyServiceFolders()
    {
        $finder = new Finder();
        $finder->directories()->in($this->directories);

        $finder->name('Service');

        $removable = [];
        foreach ($finder as $communicationFolder) {
            $removable[] = $this->getRealPath($communicationFolder);
        }

        foreach ($removable as $remove) {
            $this->recursiveRemoveDirectory($remove);
        }
    }

    /**
     * @param SplFileInfo $communicationFolder
     *
     * @return string
     */
    protected function getRealPath(SplFileInfo $communicationFolder)
    {
        return $communicationFolder->getRealPath();
    }

    /**
     * @param string $dir
     *
     * @return bool
     */
    protected function recursiveRemoveDirectory($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            if (is_dir($dir . '/' . $file)) {
                $this->recursiveRemoveDirectory($dir . '/' . $file);
            } else {
                unlink($dir . '/' . $file);
            }
        }

        return rmdir($dir);
    }

}
