<?php
namespace Testify\Helper;

use Codeception\Lib\Interfaces\DependsOnModule;
use Codeception\Module;
use Codeception\TestCase;

class ZedControllerTable extends Module implements DependsOnModule
{

    /**
     * @var \Testify\Helper\ZedBootstrap
     */
    protected $zedBootstrap;

    protected $currentData = [];

    public function _depends()
    {
        return [ZedBootstrap::class => "Should be used with ZedBootstrap only"];
    }

    /**
     * @return void
     */
    public function _before(TestCase $test)
    {
        $this->currentData = [];
    }

    /**
     * @return void
     */
    public function _inject(ZedBootstrap $bootstrap)
    {
        $this->zedBootstrap = $bootstrap;
    }

    /**
     * @param $uri
     * @param array $params
     *
     * @return void
     */
    public function listDataTable($uri, array $params = [])
    {
        $this->zedBootstrap->client->request('GET', $uri, $params);
        $response = $this->zedBootstrap->client->getInternalResponse();
        $this->zedBootstrap->seeResponseCodeIs(200);
        $this->currentData = json_decode($response->getContent(), true);
    }

    /**
     * @param int $num
     *
     * @return void
     */
    public function seeNumRecordsInTable($num)
    {
        if (!isset($this->currentData['recordsTotal'])) {
            $this->fail("recordsTotal value not set; Run successful ->listDataTable before");
        }
        $this->assertEquals($num, $this->currentData['recordsTotal'], 'records in table');
    }

    /**
     * @param int $row
     * @param array $expectedRow
     *
     * @return void
     */
    public function seeInTable($row, array $expectedRow)
    {
        if (!isset($this->currentData['data'])) {
            $this->fail("data for table not set; Run successful ->listDataTable before");
        }
        $data = $this->currentData['data'];
        if (!isset($data[$row])) {
            $this->fail("No row #$row inside in a list, current number of rows: " . count($data));
        }
        $actualRow = $data[$row];
        $this->assertEquals(
            count($expectedRow),
            count(array_intersect_assoc($expectedRow, $actualRow)),
            "Row does not contain the provided data\n"
            . "- <info>" . var_export($expectedRow, true) . "</info>\n"
            . "+ " . var_export($actualRow, true)
        );
    }

    public function seeInLastRow(array $expectedRow)
    {
        if (!isset($this->currentData['data'])) {
            $this->fail("data for table not set; Run successful ->listDataTable before");
        }
        $rowNum = count($this->currentData['data']) - 1;
        return $this->seeInTable($rowNum, $expectedRow);
    }

    public function seeInFirstRow(array $expectedRow)
    {
        return $this->seeInTable(0, $expectedRow);
    }

    /**
     * @param $row
     * @param array $expectedRow
     */
    public function dontSeeInTable($row, array $expectedRow)
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
            . "- <info>" . var_export($expectedRow, true) . "</info>\n"
            . "+ " . var_export($actualRow, true)
        );
    }

    /**
     * @param array $expectedRow
     */
    public function dontSeeInLastRow(array $expectedRow)
    {
        if (!isset($this->currentData['data'])) {
            $this->assertTrue(true);
            return;
        }
        $rowNum = count($this->currentData['data']) - 1;
        return $this->dontSeeInTable($rowNum, $expectedRow);
    }

    /**
     * @param array $expectedRow
     */
    public function dontSeeInFirstRow(array $expectedRow)
    {
        return $this->dontSeeInTable(0, $expectedRow);
    }
}
