<?php
/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Url\Business;

use SprykerFeature\Zed\Library\Sanitize\Html;
use SprykerFeature\Zed\Url\Business\Exception\UrlInvalidException;

/**
 * Parses and generates URLs based on URL parts. In favor of performance, URL parts are not validated.
 */
class Url
{

    protected $scheme;
    protected $host;
    protected $port;
    protected $username;
    protected $password;
    protected $path = '';
    protected $query = [];
    protected $fragment;

    /**
     * Factory method to create a new URL from a URL string
     *
     * @param string $url Full URL used to create a Url object
     *
     * @throws UrlInvalidException
     *
     * @return self
     */
    public static function parse($url)
    {
        static $defaults = ['scheme' => null, 'host' => null, 'path' => null, 'port' => null, 'query' => null,
            'user' => null, 'pass' => null, 'fragment' => null, ];

        $parts = parse_url($url);
        if ($parts === false) {
            throw new UrlInvalidException('Was unable to parse malformed url: ' . $url);
        }

        $parts += $defaults;

        return new static($parts);
    }

    /**
     * Create a new URL from URL parts
     *
     * @param array $url
     */
    public function __construct(array $url = [])
    {
        //$scheme, $host, $username = null, $password = null, $port = null, $path = null, QueryString $query = null, $fragment = null

        // Convert the query string into an array
        if (isset($url['query']) && !is_array($url['query'])) {
            $url['query'] = self::parseQuery($url['query']);
        }

        foreach ($url as $k => $v) {
            $this->{$k} = $v;
        }
    }

    /**
     * Returns the URL as a URL string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->build();
    }

    /**
     * Build a URL from parse_url parts. The generated URL will be a relative URL if a scheme or host are not provided.
     *
     * @return string
     */
    public function build()
    {
        $parts = $this->toArray();
        $url = $scheme = '';

        if (isset($parts['scheme'])) {
            $scheme = $parts['scheme'];
            $url .= $scheme . ':';
        }

        if (isset($parts['host'])) {
            $url .= '//';
            if (isset($parts['user'])) {
                $url .= $parts['user'];
                if (isset($parts['pass'])) {
                    $url .= ':' . $parts['pass'];
                }
                $url .=  '@';
            }

            $url .= $parts['host'];

            // Only include the port if it is not the default port of the scheme
            if (isset($parts['port'])
                && !(($scheme === 'http' && $parts['port'] === 80) || ($scheme === 'https' && $parts['port'] === 443))
            ) {
                $url .= ':' . $parts['port'];
            }
        }

        // Add the path component if present
        if (isset($parts['path']) && strlen($parts['path']) !== 0) {
            // Always ensure that the path begins with '/' if set and something is before the path
            if ($url && $parts['path'][0] !== '/' && mb_substr($url, -1)  !== '/') {
                $url .= '/';
            }
            $url .= $parts['path'];
        } else {
            $url .= '/';
        }

        // Add the query string if present
        if (!empty($parts['query'])) {
            $q = [];
            foreach ($parts['query'] as $k => $v) {
                $q[] = $this->encodeQuery($k) . '=' . $this->encodeQuery($v);
            }
            $url .= '?' . implode('&', $q);
        }

        // Ensure that # is only added to the url if fragment contains anything.
        if (isset($parts['fragment'])) {
            $url .= '#' . $parts['fragment'];
        }

        return $url;
    }

    /**
     * @return string
     */
    public function buildEscaped()
    {
        return Html::escape($this->build());
    }

    /**
     * Get the parts of the URL as an array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'scheme' => $this->scheme,
            'user' => $this->username,
            'pass' => $this->password,
            'host' => $this->host,
            'port' => $this->port,
            'path' => $this->path,
            'query' => $this->query ?: [],
            'fragment' => $this->fragment,
        ];
    }

    /**
     * Set the path part of the URL
     *
     * @param array|string $path Path string or array of path segments
     *
     * @return self
     */
    public function setPath($path)
    {
        static $pathReplace = [' ' => '%20', '?' => '%3F'];
        if (is_array($path)) {
            $path = '/' . implode('/', $path);
        }

        $this->path = strtr($path, $pathReplace);

        return $this;
    }

    /**
     * Normalize the URL so that double slashes and relative paths are removed
     *
     * @return self
     */
    public function normalizePath()
    {
        if (!$this->path || $this->path === '/' || $this->path === '*') {
            return $this;
        }

        $results = [];
        $segments = $this->getPathSegments();
        foreach ($segments as $segment) {
            if ($segment === '..') {
                array_pop($results);
            } elseif ($segment !== '.' && $segment !== '') {
                $results[] = $segment;
            }
        }

        // Combine the normalized parts and add the leading slash if needed
        $this->path = ($this->path[0] === '/' ? '/' : '') . implode('/', $results);

        return $this;
    }

    /**
     * Add a relative path to the currently set path
     *
     * @param array|string $relativePath Relative path to add
     *
     * @return self
     */
    public function addPath($relativePath)
    {
        if (is_string($relativePath)) {
            $relativePath = explode('/', $relativePath);
        }

        // Add a leading slash if needed
        $path = $this->getPath();
        foreach ($relativePath as $element) {
            if ($element !== '') {
                $path .= '/' . $element;
            }
        }

        return $this->setPath($path);
    }

    /**
     * Get the path part of the URL
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param $scheme
     *
     * @return self
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * @param $port
     *
     * @return self
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @param $host
     *
     * @return self
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get the path segments of the URL as an array
     *
     * @return array
     */
    public function getPathSegments()
    {
        return array_slice(explode('/', $this->getPath()), 1);
    }

    /**
     * Get the query part of the URL as a QueryString object
     *
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param $key
     * @param $value
     * 
     * @return self
     */
    public function addQuery($key, $value)
    {
        $this->query[$key] = $value;

        return $this;
    }

    /**
     * Set the query part of the URL
     *
     * @param array $query Query to set
     *
     * @return self
     */
    public function setQuery(array $query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @param string $query
     *
     * @return array
     */
    public static function parseQuery($query)
    {
        parse_str($query, $array);

        return $array;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function encodeQuery($value)
    {
        return urlencode($value);
    }

}
