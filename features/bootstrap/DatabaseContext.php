<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;

class DatabaseContext implements Context, SnippetAcceptingContext
{
    public static $app;

    private static $protectedTables = [
        'migrations',
    ];

    /**
     * @BeforeFeature
     */
    public static function setUp()
    {
        self::bootstrapIfNeeded();

        $db = self::$app->make('db');
        $schema = $db->connection()->getSchemaBuilder();

        $schema->getConnection()->statement("SET foreign_key_checks = 0;");
        $tableNames = $schema->getConnection()->getDoctrineSchemaManager()->listTableNames();

        foreach ($tableNames as $name) {
            if (in_array($name, self::$protectedTables)) {
                continue;
            }
            $db->table($name)->truncate();
        }

        $seeder = self::$app->make('DatabaseSeeder');
        $seeder->run();
    }

    public static function bootstrapIfNeeded()
    {
        if (! self::$app) {
            require __DIR__.'/../../bootstrap/autoload.php';
            self::$app = require_once __DIR__.'/../../bootstrap/app.php';
            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
            $kernel->bootstrap();
        }
    }
}
