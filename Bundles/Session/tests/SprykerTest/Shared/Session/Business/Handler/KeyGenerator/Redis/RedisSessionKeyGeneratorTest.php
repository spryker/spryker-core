<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Session\Business\Handler\KeyGenerator\Redis;

use Codeception\Test\Unit;
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
 * @group RedisSessionKeyGeneratorTest
 * Add your own group annotations below this line
 */
class RedisSessionKeyGeneratorTest extends Unit
{
    /**
     * @return void
     */
    public function testGenerateAddsPrefix()
    {
        $generator = new RedisSessionKeyGenerator();
        $generatedKey = $generator->generateSessionKey('foo-session-123');

        $this->assertSame('session:foo-session-123', $generatedKey);
    }
}
