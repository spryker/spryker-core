<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development\Business\Refactor\DependencyContainer;

use Spryker\Zed\Development\Business\Refactor\AbstractRefactor;
use Spryker\Zed\Development\Business\Refactor\RefactorException;
use Symfony\Component\Filesystem\Filesystem;

class RenameDependencyContainer extends AbstractRefactor
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
        $phpFiles = $this->getFiles($this->directories, '*.php');

        $filesystem = new Filesystem();

        foreach ($phpFiles as $phpFile) {
            $content = $phpFile->getContents();

            $pattern = '/DependencyContainer/';
            if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER) === 0) {
                continue;
            }

            switch ($this->getApplicationFromFileInfo($phpFile)) {
                case 'Yves':
                case 'Client':
                    $suffix = 'Factory';
                    break;
                default:
                    $suffix = $this->getLayerFromFileInfo($phpFile) . 'Factory';
            }

            $content = preg_replace('/DependencyContainer/', $suffix, $content);

            $targetFile = preg_replace('/(.*)DependencyContainer\.php$/', '$1' . $suffix . '.php', $phpFile->getPathname());

            $filesystem->dumpFile($targetFile, $content);
            echo $targetFile . PHP_EOL;

            if ($targetFile !== $phpFile->getPathname()) {
                $filesystem->remove($phpFile->getPathname());
                echo 'REMOVED! ' . $phpFile->getPathname() . PHP_EOL;
            }

        }
    }

}
