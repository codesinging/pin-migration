<?php
/**
 * Author: CodeSinging <codesinging@gmail.com>
 * Time: 2019/12/25 10:17
 */

namespace CodeSinging\PinMigration\Schema;

class Blueprint
{
    const TEXT_TINY = 255;
    const TEXT_REGULAR = 65535;
    const TEXT_MEDIUM = 16777215;
    const TEXT_LONG = 4294967295;

    const BLOB_TINY = 255;
    const BLOB_REGULAR = 65535;
    const BLOB_MEDIUM = 16777215;
    const BLOB_LONG = 4294967295;

    const INT_TINY = 255;
    const INT_SMALL = 65535;
    const INT_MEDIUM = 16777215;
    const INT_REGULAR = 4294967295;
    const INT_BIG = 18446744073709551615;

    /**
     * The columns that should be added to the table.
     * @var ColumnDefinition[]
     */
    protected $columns = [];

    /**
     * The indexes that should be added to the table.
     * @var IndexDefinition[]
     */
    protected $indexes = [];

    /**
     * Table definition instance.
     * @var TableDefinition
     */
    protected $tableDefinition;

    /**
     * Blueprint constructor.
     */
    public function __construct()
    {
        $this->tableDefinition = new TableDefinition();
    }

    /**
     * Add a new binary column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function binary(string $column)
    {
        return $this->addColumn($column, 'binary');
    }

    /**
     * Add a new boolean column on the table.
     *
     * @param string $column
     * @param bool   $default
     *
     * @return ColumnDefinition
     */
    public function boolean(string $column, bool $default=true)
    {
        return $this->addColumn($column, 'boolean')->default($default);
    }

    /**
     * Add a new char column on the table.
     *
     * @param string   $column
     * @param int|null $length
     *
     * @return ColumnDefinition
     */
    public function char(string $column, int $length=null)
    {
        $options = $length ? compact('length') : [];
        return $this->addColumn($column, 'char', $options);
    }

    /**
     * Add a new date column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function date(string $column)
    {
        return $this->addColumn($column, 'date');
    }

    /**
     * Add a new datetime column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function datetime(string $column)
    {
        return $this->addColumn($column, 'datetime');
    }

    /**
     * Add a new decimal column on the table.
     *
     * @param string $column
     * @param int    $precision Combine with scale set to set decimal accuracy
     * @param int    $scale     Combine with precision to set decimal accuracy
     *
     * @return ColumnDefinition
     */
    public function decimal(string $column, int $precision = 10, int $scale = 2)
    {
        return $this->addColumn($column, 'decimal', compact('precision', 'scale'));
    }

    /**
     * Add a new float column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function float(string $column)
    {
        return $this->addColumn($column, 'float')->default(0);
    }

    /**
     * Add a new double column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function double(string $column)
    {
        return $this->addColumn($column, 'double')->default(0);
    }

    /**
     * Add a new integer column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function integer(string $column)
    {
        return $this->addColumn($column, 'integer', ['default' => 0]);
    }

    /**
     * Add a new unsigned integer column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function unsignedInteger(string $column)
    {
        return $this->integer($column)->unsigned();
    }

    /**
     * Add a new big integer column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function bigInteger(string $column)
    {
        return $this->integer($column)->limit(self::INT_BIG);
    }

    /**
     * Add a new unsigned big integer column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function unsignedBigInteger(string $column)
    {
        return $this->bigInteger($column)->unsigned();
    }

    /**
     * Add a new medium integer column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function mediumInteger(string $column)
    {
        return $this->integer($column)->limit(self::INT_MEDIUM);
    }

    /**
     * Add a new unsigned medium integer column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function unsignedMediumInteger(string $column)
    {
        return $this->mediumInteger($column)->unsigned();
    }

    /**
     * Add a new small integer column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function smallInteger(string $column)
    {
        return $this->integer($column)->limit(self::INT_SMALL);
    }

    /**
     * Add a new unsigned small integer column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function unsignedSmallInteger(string $column)
    {
        return $this->smallInteger($column)->unsigned();
    }

    /**
     * Add a new tiny integer column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function tinyInteger(string $column)
    {
        return $this->integer($column)->limit(self::INT_TINY);
    }

    /**
     * Add a new unsigned tiny integer column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function unsignedTinyInteger(string $column)
    {
        return $this->tinyInteger($column)->unsigned();
    }

    /**
     * Add a new string column on the table.
     *
     * @param string   $column
     * @param int|null $length
     *
     * @return ColumnDefinition
     */
    public function string(string $column, int $length = null)
    {
        $options = $length ? compact('length') : [];
        return $this->addColumn($column, 'string', $options);
    }

    /**
     * Add a new text column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function text(string $column)
    {
        return $this->addColumn($column, 'text');
    }

    /**
     * Add a new long text column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function longText(string $column)
    {
        return $this->text($column)->limit(self::TEXT_LONG);
    }

    /**
     * Add a new medium text column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function mediumText(string $column)
    {
        return $this->text($column)->limit(self::TEXT_MEDIUM);
    }

    /**
     * Add a new tiny text column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function tinyText(string $column)
    {
        return $this->text($column)->limit(self::TEXT_TINY);
    }

    /**
     * Add a new text column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function time(string $column)
    {
        return $this->addColumn($column, 'time');
    }

    /**
     * Add a new timestamp column on the table.
     *
     * @param string $column
     * @param string $default
     * @param string $update
     *
     * @return ColumnDefinition
     */
    public function timestamp(string $column, string $default = null, string $update = '')
    {
        return $this->addColumn($column, 'timestamp', compact('default', 'update'))->nullable();
    }

    /**
     * Add a new current timestamp column on the table.
     *
     * @param string $column
     * @param string $default
     * @param string $update
     *
     * @return ColumnDefinition
     */
    public function currentTimestamp(string $column, string $default = 'CURRENT_TIMESTAMP', string $update = '')
    {
        return $this->addColumn($column, 'timestamp', compact('default', 'update'));
    }

    /**
     * Add a new uuid column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function uuid(string $column)
    {
        return $this->addColumn($column, 'uuid');
    }

    /**
     * Add a new enum column on the table.
     *
     * @param string $column
     *
     * @param null   $values
     *
     * @return ColumnDefinition
     */
    public function enum(string $column, $values = null)
    {
        $options = is_null($values) ? [] : compact('values');
        return $this->addColumn($column, 'enum', $options);
    }

    /**
     * Add a new set column on the table.
     *
     * @param string            $column
     * @param null|string|array $values Can be a comma separated list or an array of values
     *
     * @return ColumnDefinition
     */
    public function set(string $column, $values = null)
    {
        $options = is_null($values) ? [] : compact('values');
        return $this->addColumn($column, 'set', $options);
    }

    /**
     * Add a new blob column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function blob(string $column)
    {
        return $this->addColumn($column, 'blob');
    }

    /**
     * Add a new long blob column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function longBlob(string $column)
    {
        return $this->blob($column)->limit(self::BLOB_LONG);
    }

    /**
     * Add a new medium blob column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function mediumBlob(string $column)
    {
        return $this->blob($column)->limit(self::BLOB_MEDIUM);
    }

    /**
     * Add a new tiny blob column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function tinyBlob(string $column)
    {
        return $this->blob($column)->limit(self::BLOB_TINY);
    }

    /**
     * Add a new bit column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function bit(string $column)
    {
        return $this->addColumn($column, 'bit');
    }

    /**
     * Add a new json column on the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function json(string $column)
    {
        return $this->addColumn($column, 'json');
    }

    /**
     * Add a new timestamp column create_time to the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function createTime(string $column = 'create_time')
    {
        return $this->currentTimestamp($column);
    }

    /**
     * Add a new timestamp column update_time to the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function updateTime(string $column = 'update_time')
    {
        return $this->currentTimestamp($column)->default(null)->nullable();
    }

    /**
     * Add a new timestamp column delete_time to the table.
     *
     * @param string $column
     *
     * @return ColumnDefinition
     */
    public function deleteTime(string $column = 'delete_time')
    {
        return $this->currentTimestamp($column)->default(null)->nullable();
    }

    /**
     * Add timestamp columns create_time and update_time to the table.
     *
     * @param string $createAt
     * @param string $updateAt
     *
     * @return $this
     */
    public function timestamps(string $createAt = 'create_time', string $updateAt = 'update_time')
    {
        $this->createTime($createAt);
        $this->updateTime($updateAt);
        return $this;
    }

    /**
     * Add a boolean column status to the table.
     *
     * @param bool $default
     *
     * @return ColumnDefinition
     */
    public function status(bool $default = true)
    {
        return $this->boolean('status')->default($default);
    }

    /**
     * Add a new index to the blueprint.
     *
     * @param string|array $columns
     * @param array        $options
     *
     * @return IndexDefinition
     */
    public function index($columns, array $options = [])
    {
        $index = new IndexDefinition($columns, $options);
        $this->indexes[] = $index;

        return $index;
    }

    /**
     * Get the indexes on the blueprint.
     * @return IndexDefinition[]
     */
    public function getIndexes()
    {
        return $this->indexes;
    }

    /**
     * Add a new column to the blueprint.
     *
     * @param string      $name
     * @param string|null $type
     * @param array       $options
     *
     * @return ColumnDefinition
     */
    public function addColumn(string $name, string $type = null, array $options = [])
    {
        $column = new ColumnDefinition($name, $type, $options);
        $this->columns[] = $column;

        return $column;
    }

    /**
     * Get the columns on the blueprint.
     * @return ColumnDefinition[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Set table options.
     *
     * @param array $options
     *
     * @return TableDefinition
     */
    public function table($options = [])
    {
        $this->tableDefinition->set($options);
        return $this->tableDefinition;
    }

    /**
     * Get the table definition.
     * @return TableDefinition
     */
    public function getTableDefinition()
    {
        return $this->tableDefinition;
    }
}