<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Development\Business\Refactor\Yves;

use SprykerFeature\Zed\Development\Business\Refactor\AbstractRefactor;
use SprykerFeature\Zed\Development\Business\Refactor\RefactorException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class RemoveCommunicationLayer extends AbstractRefactor
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

                $replacedContent = $this->removeCommunicationNamespace($content);
                $replacedContent = $this->fixDependencyContainer($replacedContent);
                $replacedContent = $this->fixYvesBootstrap($replacedContent);
                $replacedContent = $this->fixAbstractRouter($replacedContent);
                $replacedContent = $this->fixRoutingHelper($replacedContent);

                if ($replacedContent !== $content) {
                    $filesystem->dumpFile($file->getPathname(), $replacedContent);
                }
            }

            $this->moveFileFromCommunicationNamespace($filesystem, $file);
        }
        $this->cleanupEmptyCommunicationFolders($filesystem);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function removeCommunicationNamespace(&$content)
    {
        return preg_replace('/(.*\\\\Yves\\\\.*)\\\\Communication\b/', '$1', $content);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function fixDependencyContainer(&$content)
    {
        return preg_replace('/AbstractCommunicationDependencyContainer/', 'AbstractDependencyContainer', $content);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function fixYvesBootstrap(&$content)
    {
        return preg_replace(
            '/SprykerEngine\\\\Yves\\\\Application\\\\YvesBootstrap/',
            'SprykerEngine\\Yves\\Application\\Bootstrap\\YvesBootstrap',
            $content
        );
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function fixAbstractRouter(&$content)
    {
        return preg_replace(
            '/SprykerEngine\\\\Yves\\\\Application\\\\Business\\\Routing\\\\AbstractRouter/',
            'SprykerEngine\\Yves\\Application\\Routing\\AbstractRouter',
            $content
        );
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function fixRoutingHelper(&$content)
    {
        return preg_replace(
            '/SprykerEngine\\\\Yves\\\\Application\\\\Business\\\Routing\\\\Helper/',
            'SprykerEngine\\Yves\\Application\\Routing\\Helper',
            $content
        );
    }

    /**
     * @param Filesystem $filesystem
     * @param SplFileInfo $phpFile
     *
     * @return void
     */
    protected function moveFileFromCommunicationNamespace(Filesystem $filesystem, SplFileInfo $phpFile)
    {
        if (preg_match('/(.*\/Yves\/.*\/)Communication\/(.*)/', $phpFile->getRealPath(), $matches)) {
            $targetFile = $matches[1] . $matches[2];

            $filesystem->copy($phpFile->getRealPath(), $targetFile);
            $filesystem->remove($phpFile->getRealPath());
        }
    }

    /**
     * @param Filesystem $filesystem
     *
     * @return void
     */
    protected function cleanupEmptyCommunicationFolders(Filesystem $filesystem)
    {
        $finder = new Finder();
        $finder->directories()->in($this->directories);

        $finder->name('Communication');

        $removable = [];
        /** @var SplFileInfo $communicationFolder */
        foreach ($finder as $communicationFolder) {
            $removable[] = $communicationFolder->getRealPath();
        }

        foreach ($removable as $remove) {
            $this->recursiveRemoveDirectory($remove);
        }
    }

    /**
     * @param string $dir
     *
     * @return bool
     */
    public function recursiveRemoveDirectory($dir) {
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
