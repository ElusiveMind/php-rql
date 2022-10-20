<?php

namespace r\Tests;

use r\ConnectionOptions;

use function r\connect;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $datasets = array();

    public function setUp(): void
    {
        $this->conn = $this->getConnection();
    }

    // return the current db connection
    protected function getConnection()
    {
        static $connection;

        if (!isset($connection)) {
            $connection = connect(
                new ConnectionOptions(host: getenv('RDB_HOST'), port: getenv('RDB_PORT'), db: getenv('RDB_DB'))
            );
        }

        return $connection;
    }

    // enable $this->db(), instead of \rdb('DB_NAME'), in tests
    protected function db()
    {
        return \r\db(getenv('RDB_DB'));
    }

    // returns the requested dataset
    protected function useDataset($name)
    {
        static $datasets;

        if (!isset($datasets[$name])) {
            $ds = 'r\Tests\Datasets\\' . $name;
            $datasets[$name] = new $ds($this->conn);
        }

        return $datasets[$name];
    }

    // test the results status
    protected function assertObStatus($status, $data)
    {
        $statuses = array(
            'unchanged',
            'skipped',
            'replaced',
            'inserted',
            'errors',
            'deleted'
        );

        foreach ($statuses as $s) {
            $status[$s] = $status[$s] ?? 0;
        }

        foreach ($statuses as $s) {
            $res[$s] = $data[$s] ?? 0;
        }

        $this->assertEquals($status, $res);
    }

    // convert a results objects (usually ArrayObject) to an array
    // works on multidimensional arrays, too
    protected function toArray($object)
    {
        return json_decode(json_encode($object), true);
    }
}
