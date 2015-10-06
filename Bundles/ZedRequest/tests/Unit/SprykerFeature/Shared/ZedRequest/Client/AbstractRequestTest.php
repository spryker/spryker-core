<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Shared\ZedRequest\Client;

use Unit\SprykerFeature\Shared\ZedRequest\Client\Fixture\AbstractRequest;

/**
 * @group SprykerFeature
 * @group Shared
 * @group ZedRequest
 * @group Client
 * @group AbstractRequest
 */
class AbstractRequestTest extends \PHPUnit_Framework_TestCase
{

    public function testGetTransferMustReturnNullIfNoTransferClassNameProvided()
    {
        $data = [];
        $abstractRequest = new AbstractRequest($data);

        $this->assertNull($abstractRequest->getTransfer());
    }

    public function testGetTransferMustReturnTransferIfTransferClassNameAndDataProvided()
    {
        $data = [
            'transferClassName' => '\\Unit\\SprykerFeature\\Shared\\ZedRequest\\Client\\Fixture\\Transfer',
            'transfer' => ['key' => 'value'],
        ];
        $abstractRequest = new AbstractRequest($data);

        $this->assertInstanceOf('SprykerEngine\Shared\Transfer\AbstractTransfer', $abstractRequest->getTransfer());
    }

    public function testGetTransferMustReturnTransferIfTransferClassNameProvidedButNoDataGiven()
    {
        $data = [
            'transferClassName' => '\\Unit\\SprykerFeature\\Shared\\ZedRequest\\Client\\Fixture\\Transfer',
        ];
        $abstractRequest = new AbstractRequest($data);

        $this->assertInstanceOf('SprykerEngine\Shared\Transfer\AbstractTransfer', $abstractRequest->getTransfer());
    }

}
