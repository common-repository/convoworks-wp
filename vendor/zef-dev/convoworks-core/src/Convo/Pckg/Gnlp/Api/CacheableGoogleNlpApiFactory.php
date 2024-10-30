<?php

declare (strict_types=1);
namespace Convo\Pckg\Gnlp\Api;

class CacheableGoogleNlpApiFactory implements \Convo\Pckg\Gnlp\Api\IGoogleNlpFactory
{
    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    private $_cache;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $_logger;
    /**
     * @var \Convo\Core\Util\IHttpFactory
     */
    private $_httpFactory;
    public function __construct(\Psr\SimpleCache\CacheInterface $cache, $logger, $httpFactory)
    {
        $this->_cache = $cache;
        $this->_logger = $logger;
        $this->_httpFactory = $httpFactory;
    }
    public function getApi($apiKey)
    {
        $nlpApi = new \Convo\Pckg\Gnlp\Api\GoogleNlpApi($apiKey, $this->_logger, $this->_httpFactory);
        return new \Convo\Pckg\Gnlp\Api\CacheableGoogleNlpApi($this->_cache, $nlpApi);
    }
    // UTIL
    public function __toString()
    {
        return \get_class($this);
    }
}
