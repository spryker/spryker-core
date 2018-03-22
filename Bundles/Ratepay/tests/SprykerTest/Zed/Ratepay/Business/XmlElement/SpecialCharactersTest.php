<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\XmlElement;

use Codeception\Test\Unit;
use Spryker\Zed\Ratepay\Business\Api\SimpleXMLElement;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group XmlElement
 * @group SpecialCharactersTest
 * Add your own group annotations below this line
 */
class SpecialCharactersTest extends Unit
{
    /**
     * @var array
     */
    protected $characters = [
        "–" => "-",
        "´" => "'",
        "‹" => "<",
        "›" => ">",
        "‘" => "'",
        "’" => "'",
        "‚" => ",",
        "“" => '"',
        "”" => '"',
        "„" => '"',
        "‟" => '"',
        "•" => "-",
        "‒" => "-",
        "―" => "-",
        "—" => "-",
        "™" => "TM",
        "¼" => "1/4",
        "½" => "1/2",
        "¾" => "3/4",
    ];

    /**
     * @return void
     */
    public function testSpecialCharacters()
    {
        $simpleXmlElement = new SimpleXMLElement('<root></root>');
        foreach ($this->characters as $character => $expected) {
            $this->assertEquals($expected, (string)$simpleXmlElement->addCDataChild('test', $character));
        }
    }
}
