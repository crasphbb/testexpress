<?php
/**
 * Express.php
 * @author huangbinbin
 * @date   2022/7/27 10:30
 */

namespace Crasp\Testexpress;

use Crasp\Testexpress\Exceptions\InvaildArgumentException;

class Express
{
    /**
     * @var array
     * @author huangbinbin
     * @date   2022/7/27 10:45
     */
    private $config;
    /**
     * 获取
     * @var
     * @author huangbinbin
     * @date   2022/7/27 11:30
     */
    private $gateway;

    /**
     * 初始化配置等基础信息
     * Express constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * 查询方法
     * @param string        $trackNumber
     * @param string|string $type
     * @param string|string $company
     *
     * @return mixed
     * @throws InvaildArgumentException
     * @author huangbinbin
     * @date   2022/7/27 10:58
     */
    public function query(string $trackNumber, string $type = 'kuaidi 100', string $company = '')
    {
        if(!$type) {
            throw new InvaildArgumentException('物流渠道不能为空');
        }
        $gateway = $this->getGateway($type);
        return $gateway->query($trackNumber, $company);
    }

    /**
     * 获取物流渠道实例
     *
     * @param string $name
     *
     * @return mixed
     * @throws InvaildArgumentException
     * @author huangbinbin
     * @date   2022/7/27 10:57
     */
    public function getGateway(string $name)
    {
        if (!isset($this->gateway[$name])) {
            $gatewayName = $this->getGatewayName($name);
            if(!class_exists($gatewayName)) {
                throw new InvaildArgumentException('物流渠道类不存在');
            }
            $this->gateway[$name] = new $gatewayName($this->config);
        }

        return $this->gateway[$name];
    }

    /**
     * 获取各个渠道的类名
     *
     * @param string $name
     *
     * @return string
     * @author huangbinbin
     * @date   2022/7/27 10:52
     */
    public function getGatewayName(string $name)
    {
        return __NAMESPACE__ . '\\Gateways\\' . \ucfirst(\strtolower(\str_replace(['-', '_', ' '], '', $name))) . 'Gateway';
    }
}