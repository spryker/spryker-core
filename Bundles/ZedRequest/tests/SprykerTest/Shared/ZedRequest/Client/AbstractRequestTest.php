<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ZedRequest\Client;

use Codeception\Test\Unit;
use SprykerTest\Shared\ZedRequest\Client\Fixture\AbstractRequest;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group ZedRequest
 * @group Client
 * @group AbstractRequestTest
 * Add your own group annotations below this line
 */
class AbstractRequestTest extends Unit
{
    /**
     * @return void
     */
    public function testGetTransferMustReturnNullIfNoTransferClassNameProvided()
    {
        $data = [];
        $abstractRequest = new AbstractRequest($data);

        $this->assertNull($abstractRequest->getTransfer());
    }

    /**
     * @return void
     */
    public function testGetTransferMustReturnTransferIfTransferClassNameAndDataProvided()
    {
        $data = [
            'transferClassName' => '\\SprykerTest\\Shared\\ZedRequest\\Client\\Fixture\\Transfer',
            'transfer' => ['key' => 'value'],
        ];
        $abstractRequest = new AbstractRequest($data);

        $this->assertInstanceOf('Spryker\Shared\Kernel\Transfer\AbstractTransfer', $abstractRequest->getTransfer());
    }

    /**
     * @return void
     */
    public function testGetTransferMustReturnTransferIfTransferClassNameProvidedButNoDataGiven()
    {
        $data = [
            'transferClassName' => '\\SprykerTest\\Shared\\ZedRequest\\Client\\Fixture\\Transfer',
        ];
        $abstractRequest = new AbstractRequest($data);

        $this->assertInstanceOf('Spryker\Shared\Kernel\Transfer\AbstractTransfer', $abstractRequest->getTransfer());
    }
}
