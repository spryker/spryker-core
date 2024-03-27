<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentValidationResponseTransfer;

/**
 * Implement this plugin to provide additional validation for comment author.
 */
interface CommentAuthorValidatorStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if the plugin is applicable for the given comment.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(CommentRequestTransfer $commentRequestTransfer): bool;

    /**
     * Specification:
     * - Validates if comment author is provided with the `CommentRequestTransfer`.
     * - Returns `CommentValidationResponseTransfer` with error message when validation failed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentValidationResponseTransfer
     */
    public function validate(CommentRequestTransfer $commentRequestTransfer): CommentValidationResponseTransfer;
}
