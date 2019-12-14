<?php
/**
 * Author: CodeSinging <codesinging@gmail.com>
 * Time: 2019/12/14 13:53
 */

namespace CodeSinging\PinMigration\Support;

use think\Db;

trait GetDbConfig
{
    /**
     * Get database configuration.
     * @return array
     */
    protected function dbConfig()
    {
        $config = Db::getConfig();

        if ($config['deploy'] == 0) {
            $dbConfig = [
                'adapter' => $config['type'],
                'host' => $config['hostname'],
                'name' => $config['database'],
                'user' => $config['username'],
                'pass' => $config['password'],
                'port' => $config['hostport'],
                'charset' => $config['charset'],
                'table_prefix' => $config['prefix'],
            ];
        } else {
            $dbConfig = [
                'adapter' => explode(',', $config['type'])[0],
                'host' => explode(',', $config['hostname'])[0],
                'name' => explode(',', $config['database'])[0],
                'user' => explode(',', $config['username'])[0],
                'pass' => explode(',', $config['password'])[0],
                'port' => explode(',', $config['hostport'])[0],
                'charset' => explode(',', $config['charset'])[0],
                'table_prefix' => explode(',', $config['prefix'])[0],
            ];
        }

        return $dbConfig;
    }
}