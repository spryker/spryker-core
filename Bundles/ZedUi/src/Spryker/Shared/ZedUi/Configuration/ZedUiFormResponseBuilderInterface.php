<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedUi\Configuration;

use Generated\Shared\Transfer\ZedUiFormResponseTransfer;

interface ZedUiFormResponseBuilderInterface
{
    /**
     * Specification:
     * - Creates `ZedUiFormResponseTransfer` according to all action settings.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ZedUiFormResponseTransfer
     */
    public function createResponse(): ZedUiFormResponseTransfer;

    /**
     * Specification:
     * - Adds message with type `info` to notification action stack.
     *
     * @api
     *
     * @param string $title
     * @param bool $closable
     * @param string|null $description
     *
     * @return $this
     */
    public function addInfoNotification(
        string $title,
        bool $closable = true,
        ?string $description = null
    );

    /**
     * Specification:
     * - Adds message with type `success` to notification action stack.
     *
     * @api
     *
     * @param string $title
     * @param bool $closable
     * @param string|null $description
     *
     * @return $this
     */
    public function addSuccessNotification(
        string $title,
        bool $closable = true,
        ?string $description = null
    );

    /**
     * Specification:
     * - Adds message with type `warning` to notification action stack.
     *
     * @api
     *
     * @param string $title
     * @param bool $closable
     * @param string|null $description
     *
     * @return $this
     */
    public function addWarningNotification(
        string $title,
        bool $closable = true,
        ?string $description = null
    );

    /**
     * Specification:
     * - Adds message with type `error` to notification action stack.
     *
     * @api
     *
     * @param string $title
     * @param bool $closable
     * @param string|null $description
     *
     * @return $this
     */
    public function addErrorNotification(
        string $title,
        bool $closable = true,
        ?string $description = null
    );

    /**
     * Specification:
     * - Adds redirect action.
     *
     * @api
     *
     * @param string $url
     *
     * @return $this
     */
    public function addActionRedirect(string $url);

    /**
     * Specification:
     * - Adds close drawer action.
     *
     * @api
     *
     * @return $this
     */
    public function addActionCloseDrawer();

    /**
     * Specification:
     * - Adds refresh table action.
     *
     * @api
     *
     * @return $this
     */
    public function addActionRefreshTable();

    /**
     * Specification:
     * - Adds refresh drawer action.
     *
     * @api
     *
     * @return $this
     */
    public function addActionRefreshDrawer();

    /**
     * Specification:
     * - Adds refresh parent table action.
     *
     * @api
     *
     * @return $this
     */
    public function addActionRefreshParentTable();
}
