<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Application;

class Version
{

    /**
     * @var string
     */
    protected static $content;

    /**
     * @return string
     */
    public static function getRevTxt()
    {
        if (empty(self::$content)) {
            self::$content = 'no rev.txt';
            $path = APPLICATION_SOURCE_DIR . '/../rev.txt';
            if (file_exists($path)) {
                self::$content = file_get_contents($path);
            }
        }

        return self::$content;
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        $content = self::getRevTxt();

        return $content !== 'no rev.txt';
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->extract('Date');
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function extract($type)
    {
        if (preg_match('~^' . $type . ': (.+)$~', self::getRevTxt(), $matches)) {
            return $matches[1];
        }

        return '';
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->extract('Path');
    }

    /**
     * @return string
     */
    public function getRevision()
    {
        return $this->extract('Revision');
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'Date' => $this->getDate(),
            'Path' => $this->getPath(),
            'Revision' => $this->getRevision(),
        ];
    }

}
