<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Resolver;

use Spryker\Zed\Api\Business\Exception\FormatterNotFoundException;
use Spryker\Zed\Api\Communication\Formatter\FormatterInterface;

class FormatterResolver implements FormatterResolverInterface
{
    /**
     * @var string
     */
    public const FORMATTER_TYPE_JSON = 'json';

    /**
     * @var array<\Closure>
     */
    protected $formatterTypeMap;

    /**
     * @param array<\Closure> $formatterTypeMap
     */
    public function __construct(array $formatterTypeMap)
    {
        $this->formatterTypeMap = $formatterTypeMap;
    }

    /**
     * @param string|null $formatType
     *
     * @throws \Spryker\Zed\Api\Business\Exception\FormatterNotFoundException
     *
     * @return \Spryker\Zed\Api\Communication\Formatter\FormatterInterface
     */
    public function resolveFormatter(?string $formatType = null): FormatterInterface
    {
        if ($formatType === null) {
            $formatType = static::FORMATTER_TYPE_JSON;
        }

        if (!array_key_exists($formatType, $this->formatterTypeMap)) {
            throw new FormatterNotFoundException(sprintf('Formatter for type "%s" not found', $formatType));
        }

        return call_user_func($this->formatterTypeMap[$formatType]);
    }
}
