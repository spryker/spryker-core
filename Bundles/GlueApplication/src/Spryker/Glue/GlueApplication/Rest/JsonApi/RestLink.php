<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\JsonApi;

class RestLink implements RestLinkInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $href;

    /**
     * @var array
     */
    protected $meta;

    /**
     * @param string $name
     * @param string $href
     * @param array|null $meta
     */
    public function __construct(string $name, string $href, ?array $meta = null)
    {
        $this->name = $name;
        $this->href = $href;
        $this->meta = $meta;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        if (!$this->meta) {
            return [$this->name => $this->href];
        }

        return [$this->name => [static::KEY_HREF => $this->href, static::KEY_META => $this->meta]];
    }
}
