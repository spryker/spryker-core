<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Business\Model;

use SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Business\Model\Helper;

class HelperTest extends \PHPUnit_Framework_TestCase
{

    public function testCallOrganizeDataMustReturnOrganizedArray()
    {
        $mock = $this->getMock('SprykerFeature\Zed\Price\Business\PriceFacade', [], [], '', false);

        $helper = new Helper($mock);

        $entity = [
            'price_types' => 'foo,bar',
            'concrete_prices' => '5.11,10.42',
        ];

        $organizedData = $helper->organizeData($entity);

        $this->assertArrayHasKey(
            'foo',
            $organizedData
        );

        $this->assertArrayHasKey(
            'bar',
            $organizedData
        );

        $this->assertEquals('5.11', $organizedData['foo']['price']);
        $this->assertEquals('10.42', $organizedData['bar']['price']);
    }

}
