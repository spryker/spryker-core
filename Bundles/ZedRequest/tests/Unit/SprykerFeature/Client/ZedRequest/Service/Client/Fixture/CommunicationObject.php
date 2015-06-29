<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Client\ZedRequest\Service\Client\Fixture;

use SprykerFeature\Shared\ZedRequest\Client\AbstractObject;

/**
 * Class CommunicationObject
 * @package Unit\SprykerFeature\Shared\ZedRequest\Client\Fixture
 */
class CommunicationObject extends AbstractObject
{

    /**
     * @var array
     */
    protected $values = [
        'test1' => null,
        'test2' => null,
        'test3' => [],
        'test4' => [null]
    ];
}
