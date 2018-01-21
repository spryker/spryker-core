<?php
namespace Spryker\Zed\FileManagerGui\Communication\Table;


use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\FileManagerGui\FileManagerGuiConstants;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class FileTable extends AbstractTable
{

    const REQUEST_ID_FILE = 'id-file';

    /**
     * @var FileManagerQueryContainer
     */
    protected $queryContainer;

    /**
     * @param FileManagerQueryContainer $queryContainer
     */
    public function __construct(FileManagerQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->setHeaders($config);
        $this->setSortableFields($config);
        $this->setSearchableFields($config);
        $this->setRawColumns($config);
        $this->setDefaultSortField($config);

        return $config;
    }


    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->queryContainer->queryFiles();
        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $item) {
            $results[] = $this->mapResults($item);
        }

        return $results;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function mapResults(array $item)
    {
        $actions = implode(' ', $this->buildLinks($item));
        
        return [
            FileManagerGuiConstants::COL_ID_FILE => $item[FileManagerGuiConstants::COL_ID_FILE],
            FileManagerGuiConstants::COL_FILE_NAME => $item[FileManagerGuiConstants::COL_FILE_NAME],
            FileManagerGuiConstants::COL_ACTIONS => $actions,
        ];
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setHeaders(TableConfiguration $config)
    {
        $config->setHeader([
            FileManagerGuiConstants::COL_ID_FILE => '#',
            FileManagerGuiConstants::COL_FILE_NAME => 'File name',
            FileManagerGuiConstants::COL_ACTIONS => FileManagerGuiConstants::COL_ACTIONS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSortableFields(TableConfiguration $config)
    {
        $config->setSortable([
            FileManagerGuiConstants::COL_ID_FILE,
            FileManagerGuiConstants::COL_FILE_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSearchableFields(TableConfiguration $config)
    {
        $config->setSearchable([
            FileManagerGuiConstants::COL_ID_FILE,
            FileManagerGuiConstants::COL_FILE_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setRawColumns(TableConfiguration $config)
    {
        $config->setRawColumns([
            FileManagerGuiConstants::COL_ACTIONS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setDefaultSortField(TableConfiguration $config)
    {
        $config->setDefaultSortField(FileManagerGuiConstants::COL_ID_FILE, FileManagerGuiConstants::SORT_DESC);
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function buildLinks($item)
    {
        $buttons = [];

        $buttons[] = $this->generateViewButton(
            Url::generate('/file-manager-gui/view', [
                static::REQUEST_ID_FILE => $item[FileManagerGuiConstants::COL_ID_FILE],
            ]),
            'View'
        );
        $buttons[] = $this->generateEditButton(
            Url::generate('/file-manager-gui/edit', [
                static::REQUEST_ID_FILE => $item[FileManagerGuiConstants::COL_ID_FILE],
            ]),
            'Edit'
        );

        return $buttons;
    }

}
