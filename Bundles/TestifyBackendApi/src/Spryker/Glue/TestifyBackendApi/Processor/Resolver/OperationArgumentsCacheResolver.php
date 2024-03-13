<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Processor\Resolver;

use ArrayObject;
use Spryker\Glue\TestifyBackendApi\Processor\Exception\OperationKeyDuplicationException;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class OperationArgumentsCacheResolver implements OperationArgumentsCacheResolverInterface
{
    /**
     * @var string
     */
    protected const KEY_IDENTIFIER = '#';

    /**
     * @var array<string, \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\ArrayObject<array-key, \Spryker\Shared\Kernel\Transfer\AbstractTransfer>>
     */
    protected static array $resolvedOperationsCache = [];

    /**
     * @param string $key
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\ArrayObject<array-key, \Spryker\Shared\Kernel\Transfer\AbstractTransfer> $dynamicFixtureOutput
     *
     * @throws \Spryker\Glue\TestifyBackendApi\Processor\Exception\OperationKeyDuplicationException
     *
     * @return void
     */
    public function add(string $key, AbstractTransfer|ArrayObject $dynamicFixtureOutput): void
    {
        if (isset(static::$resolvedOperationsCache[$key])) {
            throw new OperationKeyDuplicationException(
                sprintf('Operation key "%s" is already in the cache. Please use a different key.', $key),
            );
        }

        static::$resolvedOperationsCache[$key] = $dynamicFixtureOutput;
    }

    /**
     * @param array<string, mixed> $operationArguments
     *
     * @return array<string, mixed>
     */
    public function resolve(array $operationArguments): array
    {
        $resolvedOperationArguments = [];

        foreach ($operationArguments as $operationArgumentKey => $operationArgumentValue) {
            $resolvedOperationArguments[$operationArgumentKey] = $this->resolveArgument($operationArgumentValue);
        }

        return $resolvedOperationArguments;
    }

    /**
     * @param mixed $argumentValue
     *
     * @return mixed
     */
    protected function resolveArgument(mixed $argumentValue): mixed
    {
        if (is_array($argumentValue)) {
            return $this->resolve($argumentValue);
        }

        if (str_contains($argumentValue, static::KEY_IDENTIFIER)) {
            return $this->resolveCachedOperation($argumentValue);
        }

        return $argumentValue;
    }

    /**
     * @param string $argumentValue
     *
     * @return mixed
     */
    protected function resolveCachedOperation(string $argumentValue): mixed
    {
        $sanitizedEntityArgument = str_replace(static::KEY_IDENTIFIER, '', $argumentValue);

        if (!str_contains($sanitizedEntityArgument, '.')) {
            return $this->getFromCache($sanitizedEntityArgument);
        }

        [$operationCacheKey, $transferParameterKey] = explode('.', $sanitizedEntityArgument);
        /** @var \Spryker\Shared\Kernel\Transfer\AbstractAttributesTransfer $cacheResolvedTransfer */
        $cacheResolvedTransfer = $this->getFromCache($operationCacheKey);

        return $cacheResolvedTransfer->modifiedToArray()[$transferParameterKey];
    }

    /**
     * @param string $key
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\ArrayObject<array-key, \Spryker\Shared\Kernel\Transfer\AbstractTransfer>
     */
    protected function getFromCache(string $key): AbstractTransfer|\ArrayObject
    {
        return static::$resolvedOperationsCache[$key];
    }
}
