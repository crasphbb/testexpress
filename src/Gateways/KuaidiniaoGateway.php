<?php
/**
 * KuaidiniaoGateway.php
 * @author huangbinbin
 * @date   2022/7/27 11:31
 */

namespace Crasp\Testexpress\Gateways;


use Crasp\Testexpress\Exceptions\InvaildArgumentException;
use Overtrue\Http\Client;

class KuaidiniaoGateway extends Gateway
{
    private $url = 'https://poll.kuaidi100.com/poll/query.do';
    const COMPANY_RESOURCE = __DIR__ . '/../Sources/company.php';

    public function getClassName()
    {
        return 'kuaidiniao';
    }

    /**
     * @param string $trackNumber
     * @param string $company
     *
     * @return array
     * @throws InvaildArgumentException
     * @author huangbinbin
     * @date   2022/7/27 11:22
     */
    public function query(string $trackNumber, string $company = '')
    {
        if(!$company) {
            throw new InvalidArgumentException('物流公司代码必填');
        }
        $com = $this->getCompany($company);
        $paramJson = \json_encode([
            'com' => $com,
            'num' => $trackNumber,
        ]);
        $postData = [
            'customer' => $this->config['app_secret'],
            'sign'     => \strtoupper(\md5($paramJson .$this->config['app_key'].$this->config['app_secret'])),
            'param'    => $paramJson,
        ];
        $response = $this->httpClient->post($this->url, $postData, [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ]);

        return $this->formatResult($response);
    }

    public function formatResult(array $details)
    {
        if(isset($details['status']) && $details['status'] == 200) {
            return [
                'code' => self::SUCCESS_CODE,
                'message' => $details['message'],
                'data' => $details,
                'type' => $this->getClassName()
            ];
        }else{
            return [
                'code' => self::FAIL_CODE,
                'message' => $details['message'],
                'data' => $details,
                'type' => $this->getClassName()
            ];
        }
    }

    /**
     * 获取物流公司代码
     *
     * @param string $company
     *
     * @return mixed
     * @throws InvaildArgumentException
     * @author huangbinbin
     * @date   2022/7/27 11:26
     */
    public function getCompany(string $company)
    {
        $companyResources = require self::COMPANY_RESOURCE;
        if(!isset($companyResources[$company])) {
            throw new InvaildArgumentException($company . '物流公司暂不支持');
        }
        return $companyResources[$company][$this->getClassName()];
    }
}