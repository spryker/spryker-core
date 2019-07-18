<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CategoryExporter\Business;

interface CategoryNodeKeyInterface
{
    public const NODE_ID = 'node_id';
    public const NAME = 'name';
    public const URL = 'url';
    public const IMAGE = 'image';
    public const CHILDREN = 'children';
    public const PARENTS = 'parents';
}
