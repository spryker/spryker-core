<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\ChoiceLoader\Zed\Communication\Controller;

use Generated\Shared\Transfer\ClassInformationTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface;
use Symfony\Component\Finder\Finder;

class ZedCommunicationControllerChoiceLoader implements ChoiceLoaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    public function loadChoices(ModuleTransfer $moduleTransfer): array
    {
        $classInformationTransferCollection = [];
        $moduleControllerDirectory = $this->getPathToModulesControllerDirectory($moduleTransfer);
        if (!is_dir($moduleControllerDirectory)) {
            return $classInformationTransferCollection;
        }

        $finder = new Finder();
        $finder->in($moduleControllerDirectory)->files();

        foreach ($finder as $fileInfo) {
            $relativeClassName = str_replace(['/', '.php'], ['\\', ''], $fileInfo->getRelativePathname());
            $className = sprintf(
                '%s\\Zed\\%s\\Communication\\Controller\\%s',
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
    protected function getPathToModulesControllerDirectory(ModuleTransfer $moduleTransfer): string
    {
        $controllerDirectory = sprintf(
            '%1$ssrc/%2$s/Zed/%3$s/Communication/Controller/',
            $moduleTransfer->getOrganization()->getRootPath(),
            $moduleTransfer->getOrganization()->getName(),
            $moduleTransfer->getName()
        );

        return $controllerDirectory;
    }
}
