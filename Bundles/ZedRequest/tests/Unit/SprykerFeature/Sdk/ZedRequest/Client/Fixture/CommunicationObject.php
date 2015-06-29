<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Sdk\ZedRequest\Client\Fixture;

use SprykerFeature\Shared\ZedRequest\Client\AbstractObject;

class CommunicationObject extends AbstractObject
{
    protected $values = [
        'test1' => null,
        'test2' => null,
        'test3' => [],
        'test4' => [null]
    ];
}
