<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Table;

use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryKeyTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryKeyQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class KeyTable extends AbstractTable
{

    const ACTIONS = 'Actions';

    /**
     * @var SpyGlossaryKeyQuery
     */
    protected $keyQuery;

    /**
     * @param SpyGlossaryKeyQuery $keyQuery
     */
    public function __construct(SpyGlossaryKeyQuery $keyQuery)
    {
        $this->keyQuery = $keyQuery;
    }

    /**
     * @inheritDoc
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY => '#',
            SpyGlossaryKeyTableMap::COL_KEY => 'Name',
            SpyGlossaryKeyTableMap::COL_IS_ACTIVE => 'Is Active?',
            self::ACTIONS => self::ACTIONS,
        ]);

        $config->setUrl('table');

        $config->setSortable([
            SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY,
            SpyGlossaryKeyTableMap::COL_KEY,
        ]);

        $config->setSearchable([
            SpyGlossaryKeyTableMap::COL_KEY,
        ]);

        return $config;
    }

    /**
     * @inheritDoc
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->keyQuery;

        $lines = $this->runQuery($query, $config);
        if (!empty($lines)) {
            foreach ($lines as $key => $value) {
                $lines[$key][self::ACTIONS] = $this->buildLinks($value);
                $lines[$key][SpyGlossaryKeyTableMap::COL_IS_ACTIVE] = (true === $lines[$key][SpyGlossaryKeyTableMap::COL_IS_ACTIVE]) ? 'Yes' : 'No';
            }
        }

        return $lines;
    }

    /**
     * @param array $details
     *
     * @return string
     */
    private function buildLinks($details)
    {
        $result = '';

        $keyName = SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY;
        $idGlossaryKey = !empty($details[$keyName]) ? $details[$keyName] : false;
        if (false !== $idGlossaryKey) {
            $links = [
                'Edit' => '/glossary/key/edit/?id_glossary_key=%d',
            ];

            $result = [];
            $template = '<a href="%s" class="btn btn-xs btn-white">%s</a>';
            foreach ($links as $key => $value) {
                $result[] = sprintf($template, sprintf($value, $idGlossaryKey), $key);
            }

            $result = implode('&nbsp;&nbsp;&nbsp;', $result);
        }

        return $result;
    }

}
