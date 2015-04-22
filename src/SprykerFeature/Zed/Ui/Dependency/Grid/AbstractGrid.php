<?php

namespace SprykerFeature\Zed\Ui\Dependency\Grid;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Ui\Dependency\Plugin\GridPluginInterface;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractGrid
{
    const OUTPUT_PAYLOAD = 'content';

    /**
     * @var AutoCompletion
     */
    protected $locator;

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
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(ModelCriteria $query, Request $request, LocatorLocatorInterface $locator)
    {
        $this->query = $query;
        $this->request = $request;
        $this->locator = $locator;
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
}
