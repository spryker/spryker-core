<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Generator;

class BundleMethodGenerator extends AbstractGenerator
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'BundleAutoCompletion';
    }

    /**
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer[] $moduleTransferCollection
     *
     * @return void
     */
    public function generate(array $moduleTransferCollection): void
    {
        $namespace = $this->getNamespace();
        foreach ($moduleTransferCollection as $moduleTransfer) {
            if ($moduleTransfer->getMethods()->count() === 0) {
                continue;
            }

            $templateVariables = [
                'moduleTransfer' => $moduleTransfer,
                'namespace' => $namespace,
            ];

            $fileName = sprintf('%s.php', $moduleTransfer->getName());
            $content = $this->twig->render('ModuleInterface.twig', $templateVariables);

            $this->fileWriter->writeFile($fileName, $content, $this->options);
        }
    }
}
