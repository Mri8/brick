<?php

namespace Brick\Db;

/**
 * Base class for BulkInserter and BulkDeleter.
 */
abstract class BulkOperator
{
    /**
     * The PDO connection.
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * The name of the table to insert into.
     *
     * @var string
     */
    protected $table;

    /**
     * The name of the fields to insert.
     *
     * @var array
     */
    protected $fields;

    /**
     * The number of fields above. This is to avoid redundant count() calls.
     *
     * @var int
     */
    protected $numFields;

    /**
     * The number of records to process per query.
     *
     * @var int
     */
    private $operationsPerQuery;

    /**
     * The prepared statement to process a full batch of records.
     *
     * @var \PDOStatement
     */
    private $preparedStatement;

    /**
     * A buffer containing the pending values to process in the next batch.
     *
     * @var array
     */
    private $buffer = [];

    /**
     * The number of records in the buffer.
     *
     * @var int
     */
    private $bufferSize = 0;

    /**
     * The total number of affected rows.
     *
     * @var int
     */
    private $rowCount = 0;

    /**
     * @param \PDO   $pdo                   The PDO connection.
     * @param string $table                 The name of the table.
     * @param array  $fields                The name of the relevant fields.
     * @param int    $operationsPerQuery    The number of operations to process in a single query.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(\PDO $pdo, string $table, array $fields, int $operationsPerQuery = 1000)
    {
        $this->pdo       = $pdo;
        $this->table     = $table;
        $this->fields    = $fields;
        $this->numFields = count($fields);

        $this->operationsPerQuery = $operationsPerQuery;

        if ($operationsPerQuery < 1) {
            throw new \InvalidArgumentException('The number of operations per query must be 1 or more.');
        }

        $query = $this->getQuery($operationsPerQuery);
        $this->preparedStatement = $this->pdo->prepare($query);
    }

    /**
     * Queues an operation.
     *
     * @param mixed ...$values The values to process.
     *
     * @return bool Whether a batch has been synchronized with the database.
     *              This can be used to display progress feedback.
     *
     * @throws \InvalidArgumentException
     */
    public function queue(...$values)
    {
        $count = 0;

        foreach ($values as $value) {
            $this->buffer[] = $value;
            $count++;
        }

        if ($count !== $this->numFields) {
            $this->buffer = array_slice($this->buffer, 0, - $count);

            throw new \InvalidArgumentException('The number of values does not match the field count.');
        }

        $this->bufferSize++;

        if ($this->bufferSize === $this->operationsPerQuery) {
            $this->preparedStatement->execute($this->buffer);
            $this->rowCount += $this->preparedStatement->rowCount();

            $this->buffer = [];
            $this->bufferSize = 0;

            return true;
        }

        return false;
    }

    /**
     * Flushes the pending data to the database and commits the current transaction.
     *
     * This is to be called once after the last queue() has been processed,
     * to force flushing the remaining queued operations to the database table,
     * and commit the current transaction, if any.
     *
     * Do *not* forget to call this method after all the operations have been queued,
     * or it could result in data loss.
     *
     * @return void
     */
    public function flush()
    {
        if ($this->bufferSize !== 0) {
            $query = $this->getQuery($this->bufferSize);
            $statement = $this->pdo->prepare($query);
            $statement->execute($this->buffer);
            $this->rowCount += $statement->rowCount();

            $this->buffer = [];
            $this->bufferSize = 0;
        }
    }

    /**
     * Returns the total number of affected rows.
     *
     * @return int
     */
    public function getRowCount() : int
    {
        return $this->rowCount;
    }

    /**
     * @param int $numRecords
     *
     * @return string
     */
    abstract protected function getQuery(int $numRecords) : string;
}
