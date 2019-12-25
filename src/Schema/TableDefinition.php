<?php
/**
 * Author: CodeSinging <codesinging@gmail.com>
 * Time: 2019/12/25 16:09
 */

namespace CodeSinging\PinMigration\Schema;

/**
 * Class TableDefinition
 *
 * @method TableDefinition id(bool|string $id) Set table primary key id.
 * @method TableDefinition comment(string $comment) Set table comment.
 * @method TableDefinition engine(string $engine) Define table engine (defaults to ``InnoDB``)
 * @method TableDefinition collation(string $collation) Define table collation (defaults to ``utf8_general_ci``)
 * @method TableDefinition signed(bool $signed) Whether the primary key is signed (defaults to ``true``)
 *
 * @package CodeSinging\PinMigration\Schema
 */
class TableDefinition extends Fluent
{
    protected $options = [
        'id' => true,
        'signed' => false,
        'collation' => 'utf8mb4_general_ci',
    ];

    /**
     * TableDefinition constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct(array_merge($this->options, $options));
    }

    /**
     * Set table primary key or keys.
     *
     * @param string|array $key
     *
     * @return $this
     */
    public function primaryKey($key)
    {
        $this->options['primary_key'] = $key;
        return $this;
    }

    /**
     * Whether the primary key is unsigned.
     * @return $this
     */
    public function unsigned()
    {
        $this->signed(false);
        return $this;
    }

    /**
     * Set the table row format.
     *
     * @param $rowFormat
     *
     * @return $this
     */
    public function rowFormat($rowFormat)
    {
        $this->options['row-format'] = $rowFormat;
        return $this;
    }
}