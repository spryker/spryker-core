<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CategoryExporter\Business;

interface CategoryNodeKeyInterface
{
    /**
     * @var string
     */
    public const NODE_ID = 'node_id';
    /**
     * @var string
     */
    public const NAME = 'name';
    /**
     * @var string
     */
    public const URL = 'url';
    /**
     * @var string
     */
    public const IMAGE = 'image';
    /**
     * @var string
     */
    public const CHILDREN = 'children';
    /**
     * @var string
     */
    public const PARENTS = 'parents';
}
