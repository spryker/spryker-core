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
    const ENTITY_FILE_CREATE = 'Entity.spy_file.create';

    /**
     * Specification:
     * - This events will be used for spy_file entity changes
     *
     * @api
     */
    const ENTITY_FILE_UPDATE = 'Entity.spy_file.update';

    /**
     * Specification:
     * - This events will be used for spy_file entity deletion
     *
     * @api
     */
    const ENTITY_FILE_DELETE = 'Entity.spy_file.delete';
}
