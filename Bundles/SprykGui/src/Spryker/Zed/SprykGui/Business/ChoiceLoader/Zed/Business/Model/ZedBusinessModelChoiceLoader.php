<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\ChoiceLoader\Zed\Business\Model;

use Generated\Shared\Transfer\ClassInformationTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface;
use Symfony\Component\Finder\Finder;

class ZedBusinessModelChoiceLoader implements ChoiceLoaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    public function loadChoices(ModuleTransfer $moduleTransfer): array
    {
        $classInformationTransferCollection = [];
        $moduleBusinessDirectory = $this->getPathToModulesBusinessDirectory($moduleTransfer);
        if (!is_dir($moduleBusinessDirectory)) {
            return $classInformationTransferCollection;
        }

        $finder = new Finder();
        $finder->in($moduleBusinessDirectory)->files()->notName('/Interface.php|BusinessFactory.php|Facade.php|Exception.php/');

        foreach ($finder as $fileInfo) {
            $relativeClassName = str_replace(['/', '.php'], ['\\', ''], $fileInfo->getRelativePathname());
            $className = sprintf(
                '%s\\Zed\\%s\\Business\\%s',
                $moduleTransfer->getOrganization()->getName(),
                $moduleTransfer->getName(),
                $relativeClassName
            );
            $classInformationTransfer = new ClassInformationTransfer();
            $classInformationTransfer
                ->setFullyQualifiedClassName($className)
                ->setClassName($relativeClassName);

            $classInformationTransferCollection[] = $classInformationTransfer;
        }

        return $classInformationTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return string
     */
    protected function getPathToModulesBusinessDirectory(ModuleTransfer $moduleTransfer): string
    {
        $moduleBusinessDirectory = sprintf(
            '%1$ssrc/%2$s/Zed/%3$s/Business/',
            $moduleTransfer->getOrganization()->getRootPath(),
            $moduleTransfer->getOrganization()->getName(),
            $moduleTransfer->getName()
        );
        return $moduleBusinessDirectory;
    }
}
