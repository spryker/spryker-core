<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Generator;

interface GeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer[] $moduleTransferCollection
     *
     * @return void
     */
    public function generate(array $moduleTransferCollection): void;

    /**
     * @return string
     */
    public function getName(): string;
}
