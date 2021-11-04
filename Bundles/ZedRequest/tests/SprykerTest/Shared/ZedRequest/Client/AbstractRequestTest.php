<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ZedRequest\Client;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerTest\Shared\ZedRequest\Client\Fixture\AbstractRequest;
use SprykerTest\Shared\ZedRequest\Client\Fixture\Transfer;

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
    public function testGetTransferMustReturnNullIfNoTransferClassNameProvided(): void
    {
        $data = [];
        $abstractRequest = new AbstractRequest($data);

        $this->assertNull($abstractRequest->getTransfer());
    }

    /**
     * @return void
     */
    public function testGetTransferMustReturnTransferIfTransferClassNameAndDataProvided(): void
    {
        $data = [
            'transferClassName' => Transfer::class,
            'transfer' => ['key' => 'value'],
        ];
        $abstractRequest = new AbstractRequest($data);

        $this->assertInstanceOf(AbstractTransfer::class, $abstractRequest->getTransfer());
    }

    /**
     * @return void
     */
    public function testGetTransferMustReturnTransferIfTransferClassNameProvidedButNoDataGiven(): void
    {
        $data = [
            'transferClassName' => Transfer::class,
        ];
        $abstractRequest = new AbstractRequest($data);

        $this->assertInstanceOf(AbstractTransfer::class, $abstractRequest->getTransfer());
    }
}
