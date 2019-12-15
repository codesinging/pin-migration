<?php
/**
 * Author: CodeSinging <codesinging@gmail.com>
 * Time: 2019/12/13 22:32
 */

use CodeSinging\PinMigration\Console\Breakpoint;
use CodeSinging\PinMigration\Console\Create;
use CodeSinging\PinMigration\Console\Init;
use CodeSinging\PinMigration\Console\Refresh;
use CodeSinging\PinMigration\Console\Reset;
use CodeSinging\PinMigration\Console\Rollback;
use CodeSinging\PinMigration\Console\Run;
use CodeSinging\PinMigration\Console\SeedCreate;
use CodeSinging\PinMigration\Console\SeedRun;
use CodeSinging\PinMigration\Console\Status;
use CodeSinging\PinMigration\Console\Test;

return [
    Breakpoint::COMMAND_NAME => Breakpoint::class,
    Create::COMMAND_NAME => Create::class,
    Init::COMMAND_NAME => Init::class,
    Refresh::COMMAND_NAME => Refresh::class,
    Reset::COMMAND_NAME => Reset::class,
    Rollback::COMMAND_NAME => Rollback::class,
    Run::COMMAND_NAME => Run::class,
    SeedCreate::COMMAND_NAME => SeedCreate::class,
    SeedRun::COMMAND_NAME => SeedRun::class,
    Status::COMMAND_NAME => Status::class,
    Test::COMMAND_NAME => Test::class,
];