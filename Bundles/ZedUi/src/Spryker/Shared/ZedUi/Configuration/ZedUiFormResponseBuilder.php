<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedUi\Configuration;

use ArrayObject;
use Generated\Shared\Transfer\ZedUiFormResponseActionTransfer;
use Generated\Shared\Transfer\ZedUiFormResponseTransfer;

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
     * @var \Generated\Shared\Transfer\ZedUiFormResponseActionTransfer[]
     */
    protected $actions = [];

    /**
     * @var array[]
     */
    protected $notifications = [];

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
                    $this->notifications
                )
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
     * @param bool $closable
     * @param string|null $description
     *
     * @return \Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface
     */
    public function addInfoNotification(
        string $title,
        bool $closable = true,
        ?string $description = null
    ): ZedUiFormResponseBuilderInterface {
        return $this->addActionNotification(
            $title,
            static::RESPONSE_NOTIFICATION_TYPE_INFO,
            $closable,
            $description
        );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $title
     * @param bool $closable
     * @param string|null $description
     *
     * @return \Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface
     */
    public function addSuccessNotification(
        string $title,
        bool $closable = true,
        ?string $description = null
    ): ZedUiFormResponseBuilderInterface {
        return $this->addActionNotification(
            $title,
            static::RESPONSE_NOTIFICATION_TYPE_SUCCESS,
            $closable,
            $description
        );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $title
     * @param bool $closable
     * @param string|null $description
     *
     * @return \Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface
     */
    public function addWarningNotification(
        string $title,
        bool $closable = true,
        ?string $description = null
    ): ZedUiFormResponseBuilderInterface {
        return $this->addActionNotification(
            $title,
            static::RESPONSE_NOTIFICATION_TYPE_WARNING,
            $closable,
            $description
        );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $title
     * @param bool $closable
     * @param string|null $description
     *
     * @return \Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface
     */
    public function addErrorNotification(
        string $title,
        bool $closable = true,
        ?string $description = null
    ): ZedUiFormResponseBuilderInterface {
        return $this->addActionNotification(
            $title,
            static::RESPONSE_NOTIFICATION_TYPE_ERROR,
            $closable,
            $description
        );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $url
     *
     * @return \Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface
     */
    public function addActionRedirect(string $url): ZedUiFormResponseBuilderInterface
    {
        $this->actions[] = $this->createResponseAction(static::RESPONSE_ACTION_TYPE_REDIRECT, $url);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface
     */
    public function addActionRefreshDrawer(): ZedUiFormResponseBuilderInterface
    {
        $this->actions[] = $this->createResponseAction(static::RESPONSE_ACTION_TYPE_REFRESH_DRAWER);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface
     */
    public function addActionCloseDrawer(): ZedUiFormResponseBuilderInterface
    {
        $this->actions[] = $this->createResponseAction(static::RESPONSE_ACTION_TYPE_CLOSE_DRAWER);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string|null $tableId
     *
     * @return \Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface
     */
    public function addActionRefreshTable(?string $tableId = null): ZedUiFormResponseBuilderInterface
    {
        $zedUiFormResponseActionTransfer = $this->createResponseAction(
            static::RESPONSE_ACTION_TYPE_REFRESH_TABLE
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
     * @return \Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface
     */
    public function addActionRefreshParentTable(): ZedUiFormResponseBuilderInterface
    {
        $this->actions[] = $this->createResponseAction(static::RESPONSE_ACTION_TYPE_REFRESH_PARENT_TABLE);

        return $this;
    }

    /**
     * @param string $actionType
     * @param string|null $url
     * @param array $notifications
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
     * @param bool $closable
     * @param string|null $description
     *
     * @return \Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface
     */
    protected function addActionNotification(
        string $title,
        string $type,
        bool $closable = true,
        ?string $description = null
    ): ZedUiFormResponseBuilderInterface {
        $this->notifications[] = [
            'title' => $title,
            'type' => $type,
            'closable' => $closable,
            'description' => $description,
        ];

        return $this;
    }
}
