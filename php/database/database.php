<?php

class Database
{
    private static $hostname = 'localhost';
    private static $database = 'shopify';
    private static $username = 'shopify';
    private static $password = 'Shopify';
    private static $charset  = 'utf8mb4';

    private static function connect()
    {
        $dsn = "mysql:host=" . self::$hostname . ";dbname=" . self::$database . ";charset=" . self::$charset;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            return new PDO($dsn, self::$username, self::$password, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function select(string $sql, array $kwargs)
    {
        if ($num_stmt = substr_count($sql, '?') != $num_args = count($kwargs))
            throw new Exception("SQL statement has $num_stmt ?, $num_args arguments were given.");

        $statement = self::connect()->prepare($sql);
        $statement->execute($kwargs);

        return $statement->fetchAll();
    }

    public static function insert(string $sql, array $kwargs)
    {
        if ($num_stmt = substr_count($sql, '?') != $num_args = count($kwargs))
            throw new Exception("SQL statement has $num_stmt ?, $num_args arguments were given.");

        try {
            self::connect()->prepare($sql)->execute($kwargs);
            return true;
        } catch (PDOException $e) {
        }
    }
}
