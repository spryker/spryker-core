<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Grid;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;
use SprykerFeature\Zed\Ui\Dependency\Plugin\GridPluginInterface;
use Symfony\Component\HttpFoundation\Request;

class TranslationGrid extends AbstractGrid
{

    const GLOSSARY_KEY = 'key';

    /**
     * @var array
     */
    private $availableLocales = [];

    /**
     * @param ModelCriteria $query
     * @param Request $request
     * @param array $availableLocales
     */
    public function __construct(ModelCriteria $query, Request $request = null, array $availableLocales)
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
