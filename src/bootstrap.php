<?php
/**
 * Author: CodeSinging <codesinging@gmail.com>
 * Time: 2019/12/13 22:31
 */

use think\Console;

$commands = include __DIR__.'/commands.php';

Console::addDefaultCommands($commands);
