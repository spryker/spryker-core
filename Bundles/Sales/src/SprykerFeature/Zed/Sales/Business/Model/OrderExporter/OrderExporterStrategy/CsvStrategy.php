<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model\OrderExporter\OrderExporterStrategy;

use SprykerFeature\Zed\Sales\Business\Model\OrderExporter\OrderExporterStrategy;

/**
 * Class Csv
 * @package SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\OrderExporterStrategy
 */
class CsvStrategy extends OrderExporterStrategy
{

    const LINE_FEED = 'line_feed';

    /**
     * @var array
     */
    protected $exportableRows;

    /**
     * @var string
     */
    protected $exportableRow;

    /**
     * @var
     */
    protected $filePath;

    /**
     * @param array $result
     * @return void
     */
    public function handleElementExporterResult($result)
    {
        $this->exportableRow .= $result;
    }

    public function finishRow()
    {
        $this->exportableRows[] = $this->exportableRow;
        $this->exportableRow = '';
    }

    /**
     * @param int $count
     * @codeCoverageIgnore
     */
    public function updateExportedItemsCount($count = 1)
    {
        parent::updateExportedItemsCount($count);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function finishExport()
    {
        $this->finishRow();
        if (!isset($this->options[self::LINE_FEED])) {
            throw new \Exception('You must provide a line feed!');
        }
        $content = '';
        foreach ($this->exportableRows as $row) {
            $content .= $row . $this->options[self::LINE_FEED];
        }
        file_put_contents($this->getFilePath(), $content);
        return true;
    }

    /**
     * @param $filePath
     * @return $this
     */
    public function setFilePath($filePath)
    {
        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath));
        }
        $this->filePath = $filePath;
        return $this;
    }

    /**
     * @return string
     */
    protected function getFilePath()
    {
        return $this->filePath;
    }

}
