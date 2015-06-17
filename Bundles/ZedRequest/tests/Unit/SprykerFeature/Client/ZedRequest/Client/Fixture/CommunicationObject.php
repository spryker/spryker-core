<?php
namespace Unit\SprykerFeature\Client\ZedRequest\Client\Fixture;

use SprykerFeature\Shared\ZedRequest\Client\AbstractObject;

/**
 * Class CommunicationObject
 * @package Unit\SprykerFeature\Shared\ZedRequest\Client\Fixture
 */
class CommunicationObject extends AbstractObject
{
    protected $values = [
        'test1' => null,
        'test2' => null,
        'test3' => [],
        'test4' => [null]
    ];
}
