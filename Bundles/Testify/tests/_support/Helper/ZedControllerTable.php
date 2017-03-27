<?php
namespace Testify\Helper;

use Codeception\Lib\Interfaces\DependsOnModule;
use Codeception\TestCase;

class ZedControllerTable extends \Codeception\Module implements DependsOnModule
{
    /**
     * @var ZedBootstrap
     */
    protected $zedBootstrap;
    
    protected $currentData = [];

    public function _depends()
    {
        return [ZedBootstrap::class => "Should be used with ZedBootstrap only"];
    }

    public function _before(TestCase $test)
    {
        $this->currentData = [];
    }

    public function _inject(ZedBootstrap $bootstrap)
    {
        $this->zedBootstrap = $bootstrap;
    }

    /**
     * @param $uri
     * @param array $params
     */
    public function listDataTable($uri, array $params = [])
    {
        $response = $this->zedBootstrap->client->request('GET', $uri, $params);
        $this->zedBootstrap->seeResponseCodeIs(200);
        $this->currentData = json_decode($response);
    }

    /**
     * @param int $num
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
        $this->assertEquals(count($expectedRow), array_intersect_assoc($expectedRow, $actualRow),
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

    public function seeInFirstRow($expectedRow)
    {
        return $this->seeInTable(0, $expectedRow);
    }

}