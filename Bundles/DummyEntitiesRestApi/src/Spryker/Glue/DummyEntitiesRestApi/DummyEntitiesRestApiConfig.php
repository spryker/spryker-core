<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DummyEntitiesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class DummyEntitiesRestApiConfig extends AbstractBundleConfig
{
    public const CONTROLLER_DUMMY_ENTITIES = 'dummy-entities-resource';

    public const RESOURCE_DUMMY_ENTITIES = 'dummy-entities';

    public const ACTION_DUMMY_ENTITIES_GET = 'get';
    public const ACTION_DUMMY_ENTITIES_POST = 'post';
    public const ACTION_DUMMY_ENTITIES_PATCH = 'patch';
    public const ACTION_DUMMY_ENTITIES_DELETE = 'delete';

    public const RESPONSE_CODE_DUMMY_ENTITY_ID_NOT_SPECIFIED = 'XX01';
    public const RESPONSE_DETAIL_DUMMY_ENTITY_ID_NOT_SPECIFIED = 'Dummy entity id is not specified.';
    public const RESPONSE_CODE_DUMMY_ENTITY_NOT_FOUND = 'XX02';
    public const RESPONSE_DETAIL_DUMMY_ENTITY_NOT_FOUND = 'Dummy entity is not found.';
}
