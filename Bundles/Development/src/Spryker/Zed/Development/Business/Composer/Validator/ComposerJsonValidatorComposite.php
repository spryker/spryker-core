<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Validator;

use Generated\Shared\Transfer\ComposerJsonValidationResponseTransfer;

class ComposerJsonValidatorComposite implements ComposerJsonValidatorInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\Composer\Validator\ComposerJsonValidatorInterface[]
     */
    protected $composerJsonValidator;

    /**
     * @param \Spryker\Zed\Development\Business\Composer\Validator\ComposerJsonValidatorInterface[] $composerJsonValidator
     */
    public function __construct(array $composerJsonValidator)
    {
        $this->composerJsonValidator = $composerJsonValidator;
    }

    /**
     * @param array $composerJsonArray
     * @param \Generated\Shared\Transfer\ComposerJsonValidationResponseTransfer $composerJsonValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ComposerJsonValidationResponseTransfer
     */
    public function validate(
        array $composerJsonArray,
        ComposerJsonValidationResponseTransfer $composerJsonValidationResponseTransfer
    ): ComposerJsonValidationResponseTransfer {
        foreach ($this->composerJsonValidator as $composerJsonValidator) {
            $composerJsonValidationResponseTransfer = $composerJsonValidator->validate($composerJsonArray, $composerJsonValidationResponseTransfer);
        }

        return $composerJsonValidationResponseTransfer;
    }
}
