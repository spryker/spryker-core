<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Dependency\Grid;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Ui\Communication\Plugin\Grid\BooleanColumn;
use SprykerFeature\Zed\Ui\Communication\Plugin\Grid\DateTimeColumn;
use SprykerFeature\Zed\Ui\Communication\Plugin\Grid\DefaultColumn;
use SprykerFeature\Zed\Ui\Communication\Plugin\Grid\DefaultRowsRenderer;
use SprykerFeature\Zed\Ui\Communication\Plugin\Grid\Pagination;
use SprykerFeature\Zed\Ui\Dependency\Plugin\GridPluginInterface;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractGrid
{

    const OUTPUT_PAYLOAD = 'content';

    /**
     * @var AutoCompletion
     */
    private $locator;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ModelCriteria
     */
    protected $query;

    /**
     * @return GridPluginInterface[]
     */
    abstract public function definePlugins();

    /**
     * @param ModelCriteria $query
     * @param Request $request
     */
    public function __construct(ModelCriteria $query, Request $request = null)
    {
        $this->query = $query;
        $this->locator = Locator::getInstance();

        if (is_null($request)) {
            $request = $this->locator->application()->pluginPimple()->getApplication()['request'];
        }

        $this->request = $request;

        $this->init();
    }

    public function init() {

    }

    /**
     * @return array
     */
    public function renderData()
    {
        return [self::OUTPUT_PAYLOAD => $this->toArray()];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->locator->ui()->facade()->getGridOutput(
            $this->definePlugins(),
            $this->request->query->all(),
            $this->query
        );
    }

    protected function runQuery()
    {

    }

    /**
     * @return DefaultRowsRenderer
     */
    public function createDefaultRowRenderer()
    {
        return $this->locator->ui()->pluginGridDefaultRowsRenderer();
    }

    /**
     * @return Pagination
     */
    public function createPagination()
    {
        return $this->locator->ui()->pluginGridPagination();
    }

    /**
     * @return DefaultColumn
     */
    public function createDefaultColumn()
    {
        return $this->locator->ui()->pluginGridDefaultColumn();
    }

    /**
     * @return BooleanColumn
     */
    public function createBooleanColumn()
    {
        return $this->locator->ui()->pluginGridBooleanColumn();
    }

    /**
     * @return DateTimeColumn
     */
    public function createDateColumn()
    {
        return $this->locator->ui()->pluginGridDateTimeColumn();
    }

}
