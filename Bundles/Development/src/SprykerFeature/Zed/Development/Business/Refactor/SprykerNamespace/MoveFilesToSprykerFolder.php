<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Development\Business\Refactor\SprykerNamespace;

use SprykerFeature\Zed\Development\Business\Refactor\AbstractRefactor;
use SprykerFeature\Zed\Development\Business\Refactor\RefactorException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class MoveFilesToSprykerFolder extends AbstractRefactor
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
        $finder = new Finder();
        $finder->directories()->in($this->directories);

        $finder
            ->name('Spryker' . 'Feature')
            ->name('Spryker' . 'Engine');

        $movable = [];
        foreach ($finder as $communicationFolder) {
            $movable[] = $this->getRealPath($communicationFolder);
        }

        foreach ($movable as $folder) {
            $target = $this->renameNamespaces($folder);
            system(sprintf('git mv %s %s', $folder, $target));
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
     * @param string $content
     *
     * @return string
     */
    protected function renameNamespaces($content)
    {
        return preg_replace('/\b(Spryker' . 'Feature|Spryker' . 'Engine)\b/', 'Spryker', $content);
    }

}
