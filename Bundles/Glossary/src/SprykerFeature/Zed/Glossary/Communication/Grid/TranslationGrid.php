<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Glossary\Communication\Grid;

use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;
use Symfony\Component\HttpFoundation\Request;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class TranslationGrid extends AbstractGrid
{

    const GLOSSARY_KEY = 'glossary_key';

    /**
     * @var array
     */
    private $availableLocales = [];

    /**
     * @param ModelCriteria $query
     * @param Request $request
     */
    public function __construct(ModelCriteria $query, Request $request, array $availableLocales)
    {
        parent::__construct($query, $request);

        $this->availableLocales = $availableLocales;
    }

    /**
     * @return GridPluginInterface[]
     */
    public function definePlugins()
    {
        $plugins = [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),
            $this->createDefaultColumn()
                ->setName(self::GLOSSARY_KEY)
                ->filterable()
                ->sortable()
            ,
        ];

        foreach ($this->availableLocales as $locale) {
            $plugins[] = $this->createDefaultColumn()
                ->setName($locale)
                ->filterable()
                ->sortable();
        }

        return $plugins;
    }
}
