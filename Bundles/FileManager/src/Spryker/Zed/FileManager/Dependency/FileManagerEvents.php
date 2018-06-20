<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Dependency;

interface FileManagerEvents
{
    /**
     * Specification:
     * - This events will be used for spy_file entity creation
     *
     * @api
     */
    public const ENTITY_FILE_CREATE = 'Entity.spy_file.create';

    /**
     * Specification:
     * - This events will be used for spy_file entity changes
     *
     * @api
     */
    public const ENTITY_FILE_UPDATE = 'Entity.spy_file.update';

    /**
     * Specification:
     * - This events will be used for spy_file entity deletion
     *
     * @api
     */
    public const ENTITY_FILE_DELETE = 'Entity.spy_file.delete';

    /**
     * Specification:
     * - This events will be used for spy_file_info entity creation
     *
     * @api
     */
    public const ENTITY_FILE_INFO_CREATE = 'Entity.spy_file_info.create';

    /**
     * Specification:
     * - This events will be used for spy_file_info entity changes
     *
     * @api
     */
    public const ENTITY_FILE_INFO_UPDATE = 'Entity.spy_file_info.update';

    /**
     * Specification:
     * - This events will be used for spy_file_info entity deletion
     *
     * @api
     */
    public const ENTITY_FILE_INFO_DELETE = 'Entity.spy_file_info.delete';

    /**
     * Specification:
     * - This events will be used for spy_file_localized_attributes entity creation
     *
     * @api
     */
    public const ENTITY_FILE_LOCALIZED_ATTRIBUTES_CREATE = 'Entity.spy_file_localized_attributes.create';

    /**
     * Specification:
     * - This events will be used for spy_file_localized_attributes entity changes
     *
     * @api
     */
    public const ENTITY_FILE_LOCALIZED_ATTRIBUTES_UPDATE = 'Entity.spy_file_localized_attributes.update';

    /**
     * Specification:
     * - This events will be used for spy_file_localized_attributes entity deletion
     *
     * @api
     */
    public const ENTITY_FILE_LOCALIZED_ATTRIBUTES_DELETE = 'Entity.spy_file_localized_attributes.delete';
}
