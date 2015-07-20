<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Table;

use SprykerFeature\Zed\Glossary\Persistence\Propel\Base\SpyGlossaryTranslationQuery;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryKeyQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class GlossaryTable extends AbstractTable
{

    const ACTIONS = 'Actions';
    const ID_GLOSSARY_TRANSLATION = 'IdGlossaryTranslation';
    const GLOSSARY_KEY = 'GlossaryKey';

    /**
     * @var SpyGlossaryTranslationQuery
     */
    protected $glossaryQuery;

    /**
     * @var array
     */
    protected $locales;

    /**
     * @param SpyGlossaryTranslationQuery $glossaryQuery
     */
    public function __construct(SpyGlossaryTranslationQuery $glossaryQuery, array $locales)
    {
        $this->glossaryQuery = $glossaryQuery;
        $this->locales = $locales;
    }

    /**
     * @inheritDoc
     */
    protected function configure(TableConfiguration $config)
    {
        $headers = [
            self::ID_GLOSSARY_TRANSLATION => '#',
            self::GLOSSARY_KEY => 'Name',
        ];

        foreach ($this->locales as $key => $value) {
            $headers[$value] = $value;
        }

        $config->setSearchable($headers);

        $headers[self::ACTIONS] = self::ACTIONS;

        $config->setHeaders($headers);

        $config->setUrl('table');

        $config->setSortable([
            self::ID_GLOSSARY_TRANSLATION,
            self::GLOSSARY_KEY,
        ]);

        return $config;
    }

    /**
     * @inheritDoc
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->glossaryQuery->leftJoinGlossaryKey('keys')
            ->withColumn('keys.key', self::GLOSSARY_KEY)
            ->leftJoinLocale('locales')
            ->withColumn('locales.locale_name', 'LocaleKey')
        ;

        $lines = $this->runQuery($query, $config);
        if (!empty($lines)) {
            foreach ($lines as $key => $value) {
                $lines[$key][self::ACTIONS] = $this->buildLinks($value);
            }
        }

        return $lines;
    }

    /**
     * @param $details
     *
     * @return array|string
     */
    private function buildLinks($details)
    {
        $result = '';

        $idGlossaryTranslation = !empty($details[self::ID_GLOSSARY_TRANSLATION]) ? $details[self::ID_GLOSSARY_TRANSLATION] : false;
        if (false !== $idGlossaryTranslation) {
            $links = [
                'View' => '/glossary/view/?id_glossary_translation=%d',
                'Edit' => '/glossary/edit/?id_glossary_translation=%d',
            ];

            $result = [];
            foreach ($links as $key => $value) {
                $result[] = sprintf('<a href="%s" class="btn btn-xs btn-white">%s</a>', sprintf($value, $idCustomer), $key);
            }

            $result = implode('&nbsp;&nbsp;&nbsp;', $result);
        }

        return $result;
    }

}
