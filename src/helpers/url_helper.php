<?php

use Tml\Utils\StringUtils;

class UrlHelper
{
    public $method, $scheme, $host, $path, $query, $params;

    function __construct() {
        $this->method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
        $this->scheme = is_ssl() ? 'https' : 'http';
        $this->host = $_SERVER['HTTP_HOST'];
        $this->query = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
        $this->params = [];
        parse_str($this->query, $this->params);
        $this->path = explode('?', $_SERVER['REQUEST_URI'])[0];

        if ($this->isPrePath()) {
            if ($this->path !== '') {
                $elements = StringUtils::split($this->path, '/');
                $this->locale = array_shift($elements);
                $this->path = '/' . StringUtils::join($elements, '/');
            }
        } elseif ($this->isPreDomain()) {
            $elements = StringUtils::split($this->host, '.');
            $this->locale = array_shift($elements);
            $this->host = StringUtils::join($elements, '.');
        } elseif ($this->isParamBased()) {
            if (isset($this->params['locale']))
                $this->locale = $this->params['locale'];
        } elseif ($this->isCustomUrl()) {

        }
        parse_str($this->query, $this->params);
//        tml_log($this->to_array());
    }

    public function isPreDomain() {
        return ('pre-domain' == get_option('tml_locale_selector'));
    }

    public function isPrePath() {
        return ('pre-path' == get_option('tml_locale_selector'));
    }

    public function isCustomUrl() {
        return ('custom' == get_option('tml_locale_selector'));
    }

    public function isParamBased() {
        return ('param' == get_option('tml_locale_selector'));
    }

    public function getCustomUrlForLocale($locale) {
        return get_option('tml_locale_url_' . $locale);
    }

    public function toHomeUrl($path) {
        if (0 !== strpos($path, '/'))
            $path = '/' . $path;

        if ($this->isPrePath())
            return $this->scheme . '://' .  $this->host . '/' . $this->locale . $path;

        if ($this->isPreDomain())
            return $this->scheme . '://' .  $this->locale . '.' . $this->host . $path;

//        $param = '?';
//        if (strpos($path, '?') !== false)
//            $param = '&';
//        $param = $param . 'locale=' . $this->locale;

        return $this->scheme . '://' . $this->host . $path;
    }

    public function toSource() {
        if ($this->path == '' || $this->path == '/')
            return '/index';

        $source = str_replace('.php', '', $this->path);
        return $source;
    }

    public function to_array() {
        return [
            'method' => $this->method,
            'scheme' => $this->scheme,
            'host' => $this->host,
            'path' => $this->path,
            'query' => $this->query,
            'params' => $this->params,
            'locale' => $this->locale,
        ];
    }
}