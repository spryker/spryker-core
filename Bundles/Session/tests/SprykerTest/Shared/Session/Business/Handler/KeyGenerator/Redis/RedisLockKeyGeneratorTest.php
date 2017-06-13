<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Session\Business\Handler\KeyGenerator\Redis;

use Codeception\TestCase\Test;
use Spryker\Shared\Session\Business\Handler\KeyGenerator\Redis\RedisLockKeyGenerator;
use Spryker\Shared\Session\Business\Handler\KeyGenerator\Redis\RedisSessionKeyGenerator;

/**
 * Auto-generated group annotations
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
class RedisLockKeyGeneratorTest extends Test
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
