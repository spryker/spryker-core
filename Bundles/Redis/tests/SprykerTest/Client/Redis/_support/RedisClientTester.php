<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Redis;

use Codeception\Actor;
use ReflectionClass;
use Spryker\Client\Redis\Adapter\RedisAdapterProvider;
use Spryker\Client\Redis\Compressor\Strategy\CompressorStrategyInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class RedisClientTester extends Actor
{
    use _generated\RedisClientTesterActions;

    /**
     * @var string
     */
    public const KEY = 'redis:key';

    /**
     * @return void
     */
    public function resetClientPool(): void
    {
        $class = new ReflectionClass(RedisAdapterProvider::class);
        $property = $class->getProperty('clientPool');
        $property->setAccessible(true);
        $property->setValue([]);
    }

    /**
     * @return \Spryker\Client\Redis\Compressor\Strategy\CompressorStrategyInterface
     */
    public function createTestCompressorStrategy(): CompressorStrategyInterface
    {
        return new class implements CompressorStrategyInterface
        {
            /**
             * @param mixed $value
             *
             * @return bool
             */
            public function isCompressed(mixed $value): bool
            {
                return str_starts_with($value, 'custom');
            }

            /**
             * @param string $value
             * @param int $level
             *
             * @return string|null
             */
            public function compress(string $value, int $level): ?string
            {
                return 'custom' . $value;
            }

            /**
             * @param string $value
             *
             * @return string|null
             */
            public function decompress(string $value): ?string
            {
                return ltrim($value, 'custom');
            }
        };
    }
}
