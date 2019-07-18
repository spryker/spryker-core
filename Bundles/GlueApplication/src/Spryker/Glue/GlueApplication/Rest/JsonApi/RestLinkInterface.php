<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\JsonApi;

interface RestLinkInterface
{
    public const LINK_FIRST = 'first';
    public const LINK_LAST = 'last';
    public const LINK_NEXT = 'next';
    public const LINK_PREV = 'prev';
    public const LINK_RELATED = 'related';
    public const LINK_SELF = 'self';
    public const KEY_HREF = 'href';
    public const KEY_META = 'meta';

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return array
     */
    public function toArray(): array;
}
