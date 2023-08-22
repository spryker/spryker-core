<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKeyGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ApiKeyGuiConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Base url for a page with a list of api keys.
     *
     * @api
     *
     * @see \Spryker\Zed\ApiKeyGui\Communication\Controller\ListController::indexAction()
     *
     * @var string
     */
    public const URL_API_KEY_LIST = '/api-key-gui/list';

    /**
     * Specification:
     * - Base url for a page to update the API key.
     *
     * @api
     *
     * @see \Spryker\Zed\ApiKeyGui\Communication\Controller\EditController::indexAction()
     *
     * @var string
     */
    public const URL_API_KEY_EDIT = '/api-key-gui/edit';

    /**
     * Specification:
     * - Base url for a page to remove the API key.
     *
     * @api
     *
     * @see \Spryker\Zed\ApiKeyGui\Communication\Controller\DeleteController::indexAction()
     *
     * @var string
     */
    public const URL_API_KEY_DELETE = '/api-key-gui/delete';

    /**
     * @var int
     */
    protected const KEY_LENGTH = 32;

    /**
     * Specification:
     * - Provides the length of the API key.
     *
     * @api
     *
     * @return int
     */
    public function getApiKeyLength(): int
    {
        return static::KEY_LENGTH;
    }
}
