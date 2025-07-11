<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedUi\Configuration;

use ArrayObject;
use Generated\Shared\Transfer\ZedUiFormRequestActionTransfer;
use Generated\Shared\Transfer\ZedUiFormResponseActionTransfer;
use Generated\Shared\Transfer\ZedUiFormResponseTransfer;

/**
 * Builds a standardized JSON response for AJAX form submissions in the Zed UI.
 *
 * This builder allows controllers to create responses that trigger specific frontend
 * actions like opening/closing modals, refreshing tables, or displaying notifications,
 * enabling a more dynamic and interactive user experience without full page reloads.
 */
class ZedUiFormResponseBuilder implements ZedUiFormResponseBuilderInterface
{
    /**
     * @var string
     */
    protected const RESPONSE_ACTION_TYPE_REDIRECT = 'redirect';

    /**
     * @var string
     */
    protected const RESPONSE_ACTION_TYPE_NOTIFICATION = 'notification';

    /**
     * @var string
     */
    protected const RESPONSE_ACTION_TYPE_REFRESH_TABLE = 'refresh-table';

    /**
     * @var string
     */
    protected const RESPONSE_ACTION_TYPE_REFRESH_PARENT_TABLE = 'refresh-parent-table';

    /**
     * @var string
     */
    protected const RESPONSE_ACTION_TYPE_REFRESH_DRAWER = 'refresh-drawer';

    /**
     * @var string
     */
    protected const RESPONSE_ACTION_TYPE_CLOSE_DRAWER = 'close-drawer';

    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_TYPE_INFO = 'info';

    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_TYPE_SUCCESS = 'success';

    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_TYPE_ERROR = 'error';

    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_TYPE_WARNING = 'warning';

    /**
     * @var array<\Generated\Shared\Transfer\ZedUiFormResponseActionTransfer>
     */
    protected array $actions = [];

    /**
     * @var array<array<string, mixed>>
     */
    protected array $notifications = [];

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ZedUiFormResponseTransfer
     */
    public function createResponse(): ZedUiFormResponseTransfer
    {
        $zedUiFormResponseTransfer = (new ZedUiFormResponseTransfer())
            ->setActions(new ArrayObject($this->actions));

        if ($this->notifications) {
            $zedUiFormResponseTransfer->addAction(
                $this->createResponseAction(
                    static::RESPONSE_ACTION_TYPE_NOTIFICATION,
                    null,
                    $this->notifications,
                ),
            );
        }

        return $zedUiFormResponseTransfer;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $title
     * @param bool $closeable
     * @param string|null $description
     *
     * @return $this
     */
    public function addInfoNotification(
        string $title,
        bool $closeable = true,
        ?string $description = null
    ) {
        $this->addActionNotification(
            $title,
            static::RESPONSE_NOTIFICATION_TYPE_INFO,
            $closeable,
            $description,
        );

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $title
     * @param bool $closeable
     * @param string|null $description
     *
     * @return $this
     */
    public function addSuccessNotification(
        string $title,
        bool $closeable = true,
        ?string $description = null
    ) {
        $this->addActionNotification(
            $title,
            static::RESPONSE_NOTIFICATION_TYPE_SUCCESS,
            $closeable,
            $description,
        );

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $title
     * @param bool $closeable
     * @param string|null $description
     *
     * @return $this
     */
    public function addWarningNotification(
        string $title,
        bool $closeable = true,
        ?string $description = null
    ) {
        $this->addActionNotification(
            $title,
            static::RESPONSE_NOTIFICATION_TYPE_WARNING,
            $closeable,
            $description,
        );

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $title
     * @param bool $closeable
     * @param string|null $description
     *
     * @return $this
     */
    public function addErrorNotification(
        string $title,
        bool $closeable = true,
        ?string $description = null
    ) {
        $this->addActionNotification(
            $title,
            static::RESPONSE_NOTIFICATION_TYPE_ERROR,
            $closeable,
            $description,
        );

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $url
     *
     * @return $this
     */
    public function addActionRedirect(string $url)
    {
        $this->actions[] = $this->createResponseAction(static::RESPONSE_ACTION_TYPE_REDIRECT, $url);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return $this
     */
    public function addActionRefreshDrawer()
    {
        $this->actions[] = $this->createResponseAction(static::RESPONSE_ACTION_TYPE_REFRESH_DRAWER);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return $this
     */
    public function addActionCloseDrawer()
    {
        $this->actions[] = $this->createResponseAction(static::RESPONSE_ACTION_TYPE_CLOSE_DRAWER);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ZedUiFormRequestActionTransfer $zedUiFormRequestActionTransfer
     *
     * @return $this
     */
    public function addActionRefreshModal(ZedUiFormRequestActionTransfer $zedUiFormRequestActionTransfer)
    {
        $zedUiFormResponseActionTransfer = $this->createResponseAction('refresh-modal');

        $zedUiFormResponseActionTransfer->fromArray($zedUiFormRequestActionTransfer->toArray(), true);

        $this->actions[] = $zedUiFormResponseActionTransfer;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ZedUiFormRequestActionTransfer $zedUiFormRequestActionTransfer
     *
     * @return $this
     */
    public function addActionOpenModal(ZedUiFormRequestActionTransfer $zedUiFormRequestActionTransfer)
    {
        $zedUiFormResponseActionTransfer = $this->createResponseAction('open-modal');

        $zedUiFormResponseActionTransfer->fromArray($zedUiFormRequestActionTransfer->toArray(), true);

        $this->actions[] = $zedUiFormResponseActionTransfer;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ZedUiFormRequestActionTransfer $zedUiFormRequestActionTransfer
     *
     * @return $this
     */
    public function addActionCloseModal(ZedUiFormRequestActionTransfer $zedUiFormRequestActionTransfer)
    {
        $zedUiFormResponseActionTransfer = $this->createResponseAction('close-modal');

        $zedUiFormResponseActionTransfer->fromArray($zedUiFormRequestActionTransfer->toArray(), true);

        $this->actions[] = $zedUiFormResponseActionTransfer;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ZedUiFormRequestActionTransfer $zedUiFormRequestActionTransfer
     *
     * @return $this
     */
    public function addActionSubmitForm(ZedUiFormRequestActionTransfer $zedUiFormRequestActionTransfer)
    {
        $zedUiFormResponseActionTransfer = $this->createResponseAction('submit-form');

        $zedUiFormResponseActionTransfer->fromArray($zedUiFormRequestActionTransfer->toArray(), true);

        $this->actions[] = $zedUiFormResponseActionTransfer;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ZedUiFormRequestActionTransfer $zedUiFormRequestActionTransfer
     *
     * @return $this
     */
    public function addActionSubmitAjaxForm(ZedUiFormRequestActionTransfer $zedUiFormRequestActionTransfer)
    {
        $zedUiFormResponseActionTransfer = $this->createResponseAction('submit-ajax-form');

        $zedUiFormResponseActionTransfer->fromArray($zedUiFormRequestActionTransfer->toArray(), true);

        $this->actions[] = $zedUiFormResponseActionTransfer;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string|null $tableId
     *
     * @return $this
     */
    public function addActionRefreshTable(?string $tableId = null)
    {
        $zedUiFormResponseActionTransfer = $this->createResponseAction(
            static::RESPONSE_ACTION_TYPE_REFRESH_TABLE,
        );
        $zedUiFormResponseActionTransfer->setTableId($tableId);

        $this->actions[] = $zedUiFormResponseActionTransfer;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return $this
     */
    public function addActionRefreshParentTable()
    {
        $this->actions[] = $this->createResponseAction(static::RESPONSE_ACTION_TYPE_REFRESH_PARENT_TABLE);

        return $this;
    }

    /**
     * @param string $actionType
     * @param string|null $url
     * @param array<array<string, mixed>> $notifications
     *
     * @return \Generated\Shared\Transfer\ZedUiFormResponseActionTransfer
     */
    protected function createResponseAction(
        string $actionType,
        ?string $url = null,
        array $notifications = []
    ): ZedUiFormResponseActionTransfer {
        $zedUiFormResponseActionTransfer = (new ZedUiFormResponseActionTransfer())->setType($actionType);

        if ($url) {
            $zedUiFormResponseActionTransfer->setUrl($url);
        }
        if ($notifications) {
            $zedUiFormResponseActionTransfer->setNotifications($notifications);
        }

        return $zedUiFormResponseActionTransfer;
    }

    /**
     * @param string $title
     * @param string $type
     * @param bool $closeable
     * @param string|null $description
     *
     * @return $this
     */
    protected function addActionNotification(
        string $title,
        string $type,
        bool $closeable = true,
        ?string $description = null
    ) {
        $this->notifications[] = [
            'title' => $title,
            'type' => $type,
            'closeable' => $closeable,
            'description' => $description,
        ];

        return $this;
    }
}
