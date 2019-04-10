<?php declare(strict_types = 1);

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Router\Migrate\Yves\Migrator;

use Nette\Utils\Strings;
use PhpParser\Node\Stmt\Class_;
use Rector\Application\FileProcessor;
use Rector\FileSystemRector\Rector\AbstractFileSystemRector;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\RectorDefinition\RectorDefinition;
use Symfony\Component\Filesystem\Filesystem;
use Symplify\PackageBuilder\FileSystem\SmartFileInfo;

class ControllerProviderToRouteProviderPluginFileRector extends AbstractFileSystemRector
{
    /**
     * @var \Rector\PhpParser\Node\BetterNodeFinder
     */
    protected $fileProcessor;

    /**
     * @var \Rector\PhpParser\Node\BetterNodeFinder
     */
    protected $filesystem;

    /**
     * @var \Rector\PhpParser\Node\BetterNodeFinder
     */
    protected $betterNodeFinder;

    /**
     * @param \Rector\Application\FileProcessor $fileProcessor
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     * @param \Rector\PhpParser\Node\BetterNodeFinder $betterNodeFinder
     */
    public function __construct(FileProcessor $fileProcessor, Filesystem $filesystem, BetterNodeFinder $betterNodeFinder)
    {
        $this->fileProcessor = $fileProcessor;
        $this->filesystem = $filesystem;
        $this->betterNodeFinder = $betterNodeFinder;
    }

    /**
     * @return \Rector\RectorDefinition\RectorDefinition
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Rename *ControllerProvider.php to *RouteProviderPlugin.php');
    }

    /**
     * @param \Symplify\PackageBuilder\FileSystem\SmartFileInfo $smartFileInfo
     *
     * @return void
     */
    public function refactor(SmartFileInfo $smartFileInfo): void
    {
        $nodes = $this->parseFileInfoToNodes($smartFileInfo);

        /** @var \PhpParser\Node\Stmt\Class_ $node */
        $node = $this->betterNodeFinder->findFirstInstanceOf($nodes, Class_::class);

        if (!Strings::endsWith((string)$node->name, 'ControllerProvider')) {
            return;
        }

        $newContent = $this->fileProcessor->printToString(
            $smartFileInfo
        );

        $fileDestination = $this->createClassFileDestination($node, $smartFileInfo);

        $this->printToFilePath($newContent, $fileDestination);
    }

    /**
     * @param string $fileContent
     * @param string $fileDestination
     *
     * @return void
     */
    protected function printToFilePath(string $fileContent, string $fileDestination): void
    {
        $this->filesystem->dumpFile($fileDestination, $fileContent);
    }

    /**
     * @param \PhpParser\Node\Stmt\Class_ $classNode
     * @param \Symplify\PackageBuilder\FileSystem\SmartFileInfo $smartFileInfo
     *
     * @return string
     */
    private function createClassFileDestination(Class_ $classNode, SmartFileInfo $smartFileInfo): string
    {
        $currentDirectory = dirname($smartFileInfo->getRealPath());

        $directoryFragments = explode(DIRECTORY_SEPARATOR, $currentDirectory);
        $positionOfPlugin = array_search('Plugin', $directoryFragments);

        $newDirectory = implode(DIRECTORY_SEPARATOR, array_splice($directoryFragments, 0, $positionOfPlugin + 1));

        $newClassName = str_replace('ControllerProvider', 'RouteProviderPlugin', (string)$classNode->name);

        return $newDirectory . DIRECTORY_SEPARATOR . 'Router' . DIRECTORY_SEPARATOR . $newClassName . '.php';
    }
}
