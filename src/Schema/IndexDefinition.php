<?php
/**
 * Author: CodeSinging <codesinging@gmail.com>
 * Time: 2019/12/25 12:18
 */

namespace CodeSinging\PinMigration\Schema;

/**
 * Class IndexDefinition
 *
 * @method IndexDefinition unique(bool $unique = true) Specify the index is a unique index
 * @method IndexDefinition name(string $name) Specify a name for the index
 * @method IndexDefinition limit(int|array $limit) Set index length.
 *
 * @package CodeSinging\PinMigration\Schema
 */
class IndexDefinition extends Fluent
{

    /**
     * The columns.
     * @var string|array
     */
    protected $columns;

    /**
     * IndexDefinition constructor.
     *
     * @param       $columns
     * @param array $options
     */
    public function __construct($columns, array $options = [])
    {
        $this->columns = $columns;
        parent::__construct($options);
    }

    /**
     * Set the index is a fulltext index.
     * @return IndexDefinition
     */
    public function fullText()
    {
        $this->options['type'] = 'fulltext';
        return $this;
    }

    /**
     * Get the columns.
     * @return array|string
     */
    public function getColumns()
    {
        return $this->columns;
    }
}