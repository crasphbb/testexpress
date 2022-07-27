<?php
/**
 * Gateway.php
 * @author huangbinbin
 * @date   2022/7/27 11:32
 */

namespace Crasp\Testexpress\Gateways;


use Crasp\Testexpress\Exceptions\InvaildArgumentException;
use Overtrue\Http\Client;

abstract class Gateway
{
    const SUCCESS_CODE = 200;
    const FAIL_CODE = 500;

    /**
     * @var Client
     * @author huangbinbin
     * @date   2022/7/27 11:11
     */
    protected $httpClient;
    /**
     * @var array
     * @author huangbinbin
     * @date   2022/7/27 11:16
     */
    protected $config;

    public function __construct(array $config)
    {
        $this->httpClient = new Client();
        $this->getConfig($config);
    }

    /**
     * 获取配置
     * @param array $config
     *
     * @throws InvaildArgumentException
     * @author huangbinbin
     * @date   2022/7/27 11:16
     */
    public function getConfig(array $config)
    {
        $className = $this->getClassName();
        if(!isset($config[$className])) {
            throw new InvaildArgumentException('配置信息不存在');
        };
        $this->config = $config[$className];
    }

    //获取配置
    public function getClassName()
    {
        return strtolower(\str_replace([ __NAMESPACE__, 'Gateway','\\'], '', get_class($this)));
    }

    /**
     * 查询
     * @param string        $trackNumber
     * @param string|string $company
     *
     * @return mixed
     * @author huangbinbin
     * @date   2022/7/27 11:34
     */
    abstract public function query(string $trackNumber,string $company = '');

    /**
     * 格式化参数
     * @param array $details
     *
     * @return mixed
     * @author huangbinbin
     * @date   2022/7/27 11:34
     */
    abstract public function formatResult(array $details);
}