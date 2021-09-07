<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\JsonApi;

interface RestLinkInterface
{
    /**
     * @var string
     */
    public const LINK_FIRST = 'first';
    /**
     * @var string
     */
    public const LINK_LAST = 'last';
    /**
     * @var string
     */
    public const LINK_NEXT = 'next';
    /**
     * @var string
     */
    public const LINK_PREV = 'prev';
    /**
     * @var string
     */
    public const LINK_RELATED = 'related';
    /**
     * @var string
     */
    public const LINK_SELF = 'self';
    /**
     * @var string
     */
    public const KEY_HREF = 'href';
    /**
     * @var string
     */
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
