<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer;

use Generated\Shared\Transfer\ComposerJsonValidationRequestTransfer;
use Generated\Shared\Transfer\ComposerJsonValidationResponseTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\Development\Business\Composer\Validator\ComposerJsonValidatorInterface;

class ComposerJson implements ComposerJsonInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\Composer\Validator\ComposerJsonValidatorInterface
     */
    protected $composerJsonValidator;

    /**
     * @param \Spryker\Zed\Development\Business\Composer\Validator\ComposerJsonValidatorInterface $composerJsonValidator
     */
    public function __construct(ComposerJsonValidatorInterface $composerJsonValidator)
    {
        $this->composerJsonValidator = $composerJsonValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\ComposerJsonValidationRequestTransfer $composerJsonValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ComposerJsonValidationResponseTransfer
     */
    public function validate(ComposerJsonValidationRequestTransfer $composerJsonValidationRequestTransfer): ComposerJsonValidationResponseTransfer
    {
        $composerJsonValidationResponseTransfer = new ComposerJsonValidationResponseTransfer();
        $moduleTransfer = $composerJsonValidationRequestTransfer->getModule();
        if (!$this->hasComposerJson($moduleTransfer)) {
            return $composerJsonValidationResponseTransfer;
        }

        return $this->composerJsonValidator->validate($this->getComposerJsonAsArray($moduleTransfer), $composerJsonValidationResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return bool
     */
    protected function hasComposerJson(ModuleTransfer $moduleTransfer): bool
    {
        $composerJsonFilePath = $this->getComposerJsonFilePath($moduleTransfer);

        return file_exists($composerJsonFilePath);
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    protected function getComposerJsonAsArray(ModuleTransfer $moduleTransfer): array
    {
        $composerJsonFilePath = $this->getComposerJsonFilePath($moduleTransfer);

        return json_decode(file_get_contents($composerJsonFilePath), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return string
     */
    protected function getComposerJsonFilePath(ModuleTransfer $moduleTransfer): string
    {
        $composerJsonFilePath = sprintf('%s/composer.json', $moduleTransfer->getPath());

        return $composerJsonFilePath;
    }
}
