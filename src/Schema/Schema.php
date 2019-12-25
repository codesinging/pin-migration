<?php
/**
 * Author: CodeSinging <codesinging@gmail.com>
 * Time: 2019/12/25 10:02
 */

namespace CodeSinging\PinMigration\Schema;

use Phinx\Db\Table;

class Schema
{
    /**
     * Create table.
     *
     * @param Table    $table
     * @param \Closure $closure
     */
    public static function create(Table $table, \Closure $closure)
    {
        self::run($table, $closure);

        $table->create();
    }

    /**
     * Update table.
     *
     * @param Table    $table
     * @param \Closure $closure
     */
    public static function update(Table $table, \Closure $closure)
    {
        self::run($table, $closure);

        $table->update();
    }

    /**
     * Update or create table.
     *
     * @param Table    $table
     * @param \Closure $closure
     */
    public static function save(Table $table, \Closure $closure)
    {
        self::run($table, $closure);

        $table->save();
    }

    /**
     * @param Table    $table
     * @param \Closure $closure
     */
    protected static function run(Table $table, \Closure $closure)
    {
        $blueprint = new Blueprint();

        $closure($blueprint, $table);

        $columns = $blueprint->getColumns();
        foreach ($columns as $column) {
            $table->addColumn($column->getName(), $column->getType(), $column->getOptions());

            $index = $column->getIndex();
            if (false !== $index) {
                $blueprint->index($column->getName(), $index);
            }
        }

        $indexes = $blueprint->getIndexes();
        foreach ($indexes as $index) {
            $table->addIndex($index->getColumns(), $index->getOptions());
        }

        $tableDefinition = $blueprint->getTableDefinition();
        $table->getTable()->setOptions(array_merge($table->getTable()->getOptions(), $tableDefinition->getOptions()));
    }
}