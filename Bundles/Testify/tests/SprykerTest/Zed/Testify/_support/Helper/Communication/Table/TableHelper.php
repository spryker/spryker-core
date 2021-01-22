<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Testify\Helper\Communication\Table;

use Codeception\Module;
use Codeception\TestInterface;
use SprykerTest\Zed\Application\Helper\ApplicationHelperTrait;

class TableHelper extends Module
{
    use ApplicationHelperTrait;

    /**
     * @var array
     */
    protected $currentData = [];

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->currentData = [];
    }

    /**
     * @param string $uri
     * @param array $params
     *
     * @return void
     */
    public function listDataTable(string $uri, array $params = []): void
    {
        $client = $this->getApplicationHelper()->getClient();

        $client->request('GET', $uri, $params);
        $response = $client->getResponse();
        $this->getApplicationHelper()->seeResponseCodeIs(200);
        $this->currentData = json_decode($response->getContent(), true);
    }

    /**
     * @return void
     */
    public function seeDataTable(): void
    {
        if (!isset($this->currentData['recordsTotal'])) {
            $this->fail('recordsTotal value not set; Run successful ->listDataTable before');
        }
    }

    /**
     * @param int $num
     *
     * @return void
     */
    public function seeNumRecordsInTable(int $num): void
    {
        if (!isset($this->currentData['recordsTotal'])) {
            $this->fail('recordsTotal value not set; Run successful ->listDataTable before');
        }
        $this->assertSame($num, $this->currentData['recordsTotal'], 'records in table');
    }

    /**
     * @param int $row
     * @param array $expectedRow
     *
     * @return void
     */
    public function seeInTable(int $row, array $expectedRow): void
    {
        if (!isset($this->currentData['data'])) {
            $this->fail('data for table not set; Run successful ->listDataTable before');
        }
        $data = $this->currentData['data'];
        if (!isset($data[$row])) {
            $this->fail("No row #$row inside in a list, current number of rows: " . count($data));
        }
        $actualRow = $data[$row];
        $this->assertSame(
            count($expectedRow),
            count(array_intersect_assoc($expectedRow, $actualRow)),
            'Row does not contain the provided data' . PHP_EOL
            . '- <info>' . var_export($expectedRow, true) . '</info>' . PHP_EOL
            . '+ ' . var_export($actualRow, true)
        );
    }

    /**
     * @param int $rowPosition
     *
     * @return void
     */
    public function clickDataTableEditButton(int $rowPosition = 0): void
    {
        $this->clickDataTableButton('Edit', $rowPosition);
    }

    /**
     * @param int $rowPosition
     *
     * @return void
     */
    public function clickDataTableViewButton(int $rowPosition = 0): void
    {
        $this->clickDataTableButton('View', $rowPosition);
    }

    /**
     * @param int $rowPosition
     *
     * @return void
     */
    public function clickDataTableDeleteButton(int $rowPosition = 0): void
    {
        $this->clickDataTableButton('Delete', $rowPosition);
    }

    /**
     * @param string $name
     * @param int $rowPosition
     *
     * @return void
     */
    public function clickDataTableButton(string $name, int $rowPosition = 0): void
    {
        if (!isset($this->currentData['data'])) {
            $this->fail('Data for table not set; Run successful ->listDataTable before');
        }

        if (count($this->currentData['data']) < $rowPosition) {
            $this->fail(sprintf(
                'Current data set has only "%d" number of entries. The requested row "%d" doesn\'t exists.',
                count($this->currentData['data']),
                $rowPosition
            ));
        }

        $rowData = $this->currentData['data'][$rowPosition];

        $rowData = array_reverse($rowData);

        foreach ($rowData as $rowContent) {
            $xhtml = sprintf('<html>%s</html>', $rowContent);
            $xhtml = simplexml_load_string($xhtml);
            $xpath = sprintf('//a[contains(., "%1$s")] | //button[contains(., "%1$s")]', $name);

            $elements = $xhtml->xpath($xpath);
            if ($elements) {
                $element = current($xhtml->xpath($xpath));
                /** @var \SimpleXMLElement $attributes */
                $attributes = (array)$element->attributes();

                $link = $attributes['@attributes']['href'];
            }

            if (isset($link)) {
                $this->getApplicationHelper()->amOnPage($link);
                $this->getApplicationHelper()->seeResponseCodeIs(200);

                return;
            }
        }

        $this->fail(sprintf('Couldn\'t find "%s" link in row "%d"', $name, $rowPosition));
    }

    /**
     * @param array $expectedRow
     *
     * @return void
     */
    public function seeInLastRow(array $expectedRow): void
    {
        if (!isset($this->currentData['data'])) {
            $this->fail('data for table not set; Run successful ->listDataTable before');
        }
        $rowNum = count($this->currentData['data']) - 1;

        $this->seeInTable($rowNum, $expectedRow);
    }

    /**
     * @param array $expectedRow
     *
     * @return void
     */
    public function seeInFirstRow(array $expectedRow): void
    {
        $this->seeInTable(0, $expectedRow);
    }

    /**
     * @param int $row
     * @param array $expectedRow
     *
     * @return void
     */
    public function dontSeeInTable(int $row, array $expectedRow): void
    {
        if (!isset($this->currentData['data'])) {
            $this->assertTrue(true);

            return;
        }
        $data = $this->currentData['data'];
        if (!isset($data[$row])) {
            $this->assertTrue(true);

            return;
        }
        $actualRow = $data[$row];
        $this->assertNotEquals(
            count($expectedRow),
            count(array_intersect_assoc($expectedRow, $actualRow)),
            "Row accidentally contains the provided data\n"
            . '- <info>' . var_export($expectedRow, true) . "</info>\n"
            . '+ ' . var_export($actualRow, true)
        );
    }

    /**
     * @param array $expectedRow
     *
     * @return void
     */
    public function dontSeeInLastRow(array $expectedRow): void
    {
        if (!isset($this->currentData['data'])) {
            $this->assertTrue(true);

            return;
        }
        $rowNum = count($this->currentData['data']) - 1;

        $this->dontSeeInTable($rowNum, $expectedRow);
    }

    /**
     * @param array $expectedRow
     *
     * @return void
     */
    public function dontSeeInFirstRow(array $expectedRow): void
    {
        $this->dontSeeInTable(0, $expectedRow);
    }
}
