<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace YvesUnit\SprykerEngine\Yves\Kernel\Fixtures\AbstractPlugin;

use SprykerEngine\Yves\Kernel\Factory as CommunicationFactory;

class Factory extends CommunicationFactory
{

    /**
     * @var string
     */
    protected $classNamePattern =
        '\\YvesUnit\\{{namespace}}\\Yves\\{{bundle}}{{store}}\\Fixtures\\AbstractPlugin\\Plugin\\'
    ;

}
