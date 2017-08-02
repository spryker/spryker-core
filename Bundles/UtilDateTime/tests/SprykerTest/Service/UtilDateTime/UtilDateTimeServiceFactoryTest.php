<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilDateTime;

use PHPUnit_Framework_TestCase;
use Spryker\Service\UtilDateTime\Model\DateTimeFormatterInterface;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceFactory;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group UtilDateTime
 * @group UtilDateTimeServiceFactoryTest
 * Add your own group annotations below this line
 */
class UtilDateTimeServiceFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCreateDateFormatterShouldReturnDateFormatter()
    {
        $utilDateTimeServiceFactory = new UtilDateTimeServiceFactory();

        $this->assertInstanceOf(DateTimeFormatterInterface::class, $utilDateTimeServiceFactory->createDateFormatter());
    }

}
