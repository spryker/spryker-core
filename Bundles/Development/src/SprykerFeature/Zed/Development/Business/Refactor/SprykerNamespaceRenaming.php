<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Development\Business\Refactor;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class SprykerNamespaceRenaming extends AbstractRefactor
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

            $this->moveFileToSprykerNamespace($filesystem, $file);
        }

        $this->cleanupEmptyFolders();
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function renameNamespaces($content)
    {
        return preg_replace('/\b(SprykerFeature|SprykerEngine)\b/', 'Spryker', $content);
    }

    /**
     * @param Filesystem $filesystem
     * @param SplFileInfo $phpFile
     *
     * @return void
     */
    protected function moveFileToSprykerNamespace(Filesystem $filesystem, SplFileInfo $phpFile)
    {
        if (preg_match('/\b(SprykerFeature|SprykerEngine)\b/', $phpFile->getRealPath(), $matches)) {
            $targetFile = preg_replace('/\b(SprykerFeature|SprykerEngine)\b/', 'Spryker', $phpFile->getRealPath());

            if ($filesystem->exists($targetFile)) {
                echo sprintf('File already exists, please resolve manually: %s' . PHP_EOL, $phpFile->getRealPath());
                return;
            }

            $filesystem->copy($phpFile->getRealPath(), $targetFile);
            $filesystem->remove($phpFile->getRealPath());
        }
    }

    /**
     * @return void
     */
    protected function cleanupEmptyFolders()
    {
        $finder = new Finder();
        $finder->directories()->in($this->directories);

        $finder
            ->name('SprykerFeature')
            ->name('SprykerEngine');

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
                echo sprintf('Directory is not empty for removal: %s' . PHP_EOL, $dir);
                return false;
            }
        }

        return rmdir($dir);
    }

}
