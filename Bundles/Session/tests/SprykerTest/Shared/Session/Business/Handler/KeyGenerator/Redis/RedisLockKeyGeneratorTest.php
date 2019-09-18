<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Session\Business\Handler\KeyGenerator\Redis;

use Codeception\Test\Unit;
use Spryker\Shared\Session\Business\Handler\KeyGenerator\Redis\RedisLockKeyGenerator;
use Spryker\Shared\Session\Business\Handler\KeyGenerator\Redis\RedisSessionKeyGenerator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Session
 * @group Business
 * @group Handler
 * @group KeyGenerator
 * @group Redis
 * @group RedisLockKeyGeneratorTest
 * Add your own group annotations below this line
 */
class RedisLockKeyGeneratorTest extends Unit
{
    /**
     * @return void
     */
    public function testGenerateAddsSuffix()
    {
        $generator = new RedisLockKeyGenerator(new RedisSessionKeyGenerator());
        $generatedKey = $generator->generateLockKey('foo-session-123');

        $this->assertSame('session:foo-session-123:lock', $generatedKey);
    }
}
