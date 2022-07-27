<?php
/**
 * index.php
 * @author huangbinbin
 * @date   2022/7/27 10:59
 */


use Crasp\Testexpress\Express;

require __DIR__ . '/vendor/autoload.php';

$config = [
    'kuaidi100' => [
        'app_secret' => 'F0E82A3A750CCAB123E009B60B31264E',
        'app_key'    => 'FTwkeoNA1046',
    ],'kuaidiniao' => [
        'app_secret' => 'F0E82A3A750CCAB123E009B60B31264E',
        'app_key'    => 'FTwkeoNA1046',
    ],
];
$express = new Express($config);
print_r($express->query('SF1142908617509','kuaidiniao','顺丰速运'));