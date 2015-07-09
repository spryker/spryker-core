<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Lumberjack\Code\Log;

/**
 * @group lumberjack
 */
class DataTest extends \PHPUnit_Framework_TestCase
{

    const CONTENT_CHARACTER = 'X';
    const MAX_FIELD_SIZE_IN_KILO_BYTES = 50;

    public function testFieldDataShouldNotBeLimited()
    {
        $data = new Data();
        $data->addField('data1', $this->getTestData(self::MAX_FIELD_SIZE_IN_KILO_BYTES));

        $this->assertEquals(self::CONTENT_CHARACTER, substr($data->getData()['data1'], 0, 1));
    }

    public function testFieldDataShouldBeLimited()
    {
        $data = new Data();
        // append one additional character so that the field size exceeds the allowed maximum length
        $data->addField('data1', $this->getTestData(self::MAX_FIELD_SIZE_IN_KILO_BYTES) . self::CONTENT_CHARACTER);
        $this->assertNotEquals(self::CONTENT_CHARACTER, substr($data->getData()['data1'], 0, 1));
    }

    public function testMaximumMessageSizeShouldNotBeExceededForMessagesThatFitIntoTheQuota()
    {
        $data = new Data();
        $data->addField('data1', $this->getTestData(self::MAX_FIELD_SIZE_IN_KILO_BYTES));
        $data->addField('data2', $this->getTestData(self::MAX_FIELD_SIZE_IN_KILO_BYTES));
        $data->addField('data3', $this->getTestData(self::MAX_FIELD_SIZE_IN_KILO_BYTES));
        $data->addField('data4', $this->getTestData(self::MAX_FIELD_SIZE_IN_KILO_BYTES));
        $data->addField('data5', $this->getTestData(self::MAX_FIELD_SIZE_IN_KILO_BYTES));

        $this->assertEquals(5, count($data->getData()));
    }

    public function testMaximumMessageSizeShouldNotBeExceededForMessagesThatDoNotFitIntoTheQuota()
    {
        $data = new Data();
        $data->addField('data1', $this->getTestData(self::MAX_FIELD_SIZE_IN_KILO_BYTES));
        $data->addField('data2', $this->getTestData(self::MAX_FIELD_SIZE_IN_KILO_BYTES));
        $data->addField('data3', $this->getTestData(self::MAX_FIELD_SIZE_IN_KILO_BYTES));
        $data->addField('data4', $this->getTestData(self::MAX_FIELD_SIZE_IN_KILO_BYTES));
        $data->addField('data5', $this->getTestData(self::MAX_FIELD_SIZE_IN_KILO_BYTES));
        $data->addField('data6', $this->getTestData(self::MAX_FIELD_SIZE_IN_KILO_BYTES));
        $this->assertEquals(5, count($data->getData()));
    }

    public function testMaximumMessageSizeShouldNotBeExceededForMessagesThatAreVerySmall()
    {
        $data = new Data();
        for ($i = 0; $i < 100; $i++) {
            $data->addField('data' . $i, self::CONTENT_CHARACTER);
        }

        $this->assertEquals(100, count($data->getData()));
    }

    /**
     * @param int $sizeInKiloBytes
     *
     * @return string
     */
    public function getTestData($sizeInKiloBytes)
    {
        $data = '';
        for ($i = 0; $i < ($sizeInKiloBytes * 1024); $i++) {
            $data .= self::CONTENT_CHARACTER;
        }

        return $data;
    }

}
