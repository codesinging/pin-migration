<?php
/**
 * Author: CodeSinging <codesinging@gmail.com>
 * Time: 2019/12/25 11:06
 */

namespace CodeSinging\PinMigration\Schema;

/**
 * Class ColumnDefinition
 *
 * @method ColumnDefinition limit(int $length) Set maximum length for strings
 * @method ColumnDefinition length(int $length) Set maximum length for strings
 * @method ColumnDefinition default(mixed $value) Set default value for the column
 * @method ColumnDefinition null(bool $value = true) Allow NULL values (should not be used with primary keys!)
 * @method ColumnDefinition after(string $column) Specify the column that a new column should be placed after (only applies to MySQL)
 * @method ColumnDefinition comment(string $comment) Set a text comment on the column
 *
 * @method ColumnDefinition collation(string $collation) Set collation that differs from table defaults (only applies to MySQL)
 * @method ColumnDefinition encoding(string $collation) Set character set that differs from table defaults (only applies to MySQL)
 *
 * @method ColumnDefinition signed(bool $signed = true) Enable or disable the unsigned option (only applies to MySQL)
 * @method ColumnDefinition identity(bool $signed = true) Enable or disable automatic incrementing
 *
 * @package CodeSinging\PinMigration\Schema
 */
class ColumnDefinition extends Fluent
{
    /**
     * The column name.
     * @var string
     */
    protected $name;

    /**
     * The column type.
     * @var string
     */
    protected $type;

    /**
     * The index options.
     * @var array|false
     */
    protected $index = false;

    /**
     * ColumnDefinition constructor.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     */
    public function __construct(string $name, string $type, $options = [])
    {
        $this->name = $name;
        $this->type = $type;

        parent::__construct($options);
    }

    /**
     * Enable or disable the unsigned option (only applies to MySQL).
     * @return ColumnDefinition
     */
    public function unsigned()
    {
        return $this->signed(false);
    }

    /**
     * Enable or disable automatic incrementing.
     * @return ColumnDefinition
     */
    public function autoIncrement()
    {
        return $this->identity(true);
    }

    /**
     * Allow NULL values (should not be used with primary keys!).
     *
     * @param bool $nullable
     *
     * @return ColumnDefinition
     */
    public function nullable(bool $nullable = true)
    {
        return $this->null($nullable);
    }

    /**
     * Set index for this column.
     *
     * @param array $options
     *
     * @return $this
     */
    public function index(array $options = [])
    {
        $this->index = $options;
        return $this;
    }

    /**
     * Set unique index for this column.
     *
     * @param array $options
     *
     * @return $this
     */
    public function unique(array $options = [])
    {
        $options['unique'] = true;
        return $this->index($options);
    }

    /**
     * Get index options.
     * @return array|false
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Get the column name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the column type.
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}