<?php

namespace Brick\Tests\Db;

use Brick\Db\BulkInserter;

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for class BulkInserter.
 */
class BulkInserterTest extends TestCase
{
    public function testNoTransaction()
    {
        $pdo = new PDOMock();
        $bulk = new BulkInserter($pdo, 'transactions', ['user', 'currency', 'amount'], 3, 0);

        $bulk->queue(1, 'EUR', '1.23');
        $bulk->queue(2, 'USD', '2.34');
        $bulk->queue(3, 'GBP', '3.45');
        $bulk->queue(4, 'CAD', '4.56');

        $bulk->flush();

        $expectedLog = [
            "PREPARE INSERT INTO transactions (user, currency, amount) VALUES (?, ?, ?), (?, ?, ?), (?, ?, ?)",
            "EXECUTE STATEMENT 1 (1, 'EUR', '1.23', 2, 'USD', '2.34', 3, 'GBP', '3.45')",
            "PREPARE INSERT INTO transactions (user, currency, amount) VALUES (?, ?, ?)",
            "EXECUTE STATEMENT 2 (4, 'CAD', '4.56')"
        ];

        $this->assertSame($expectedLog, $pdo->getLog());
    }

    public function testTransactionForSingleQuery()
    {
        $pdo = new PDOMock();
        $bulk = new BulkInserter($pdo, 'users', ['id', 'name'], 2, 1);

        $bulk->queue(1, 'Bob');
        $bulk->queue(2, 'Ben');
        $bulk->queue(3, 'Dan');
        $bulk->queue(4, 'Luc');
        $bulk->queue(5, 'Rod');
        $bulk->queue(6, 'Tom');

        $bulk->flush();

        $expectedLog = [
            "PREPARE INSERT INTO users (id, name) VALUES (?, ?), (?, ?)",
            "START TRANSACTION",
            "EXECUTE STATEMENT 1 (1, 'Bob', 2, 'Ben')",
            "COMMIT",
            "START TRANSACTION",
            "EXECUTE STATEMENT 1 (3, 'Dan', 4, 'Luc')",
            "COMMIT",
            "START TRANSACTION",
            "EXECUTE STATEMENT 1 (5, 'Rod', 6, 'Tom')",
            "COMMIT"
        ];

        $this->assertSame($expectedLog, $pdo->getLog());
    }

    public function testTransactionEveryTwoQueries()
    {
        $pdo = new PDOMock();
        $bulk = new BulkInserter($pdo, 'users', ['id', 'name'], 3, 2);

        $bulk->queue(1, 'Bob');
        $bulk->queue(2, 'Ben');
        $bulk->queue(3, 'Dan');
        $bulk->queue(4, 'Luc');
        $bulk->queue(5, 'Rod');
        $bulk->queue(6, 'Tom');
        $bulk->queue(7, 'Zoe');

        $bulk->flush();

        $expectedLog = [
            "PREPARE INSERT INTO users (id, name) VALUES (?, ?), (?, ?), (?, ?)",
            "START TRANSACTION",
            "EXECUTE STATEMENT 1 (1, 'Bob', 2, 'Ben', 3, 'Dan')",
            "EXECUTE STATEMENT 1 (4, 'Luc', 5, 'Rod', 6, 'Tom')",
            "COMMIT",
            "START TRANSACTION",
            "PREPARE INSERT INTO users (id, name) VALUES (?, ?)",
            "EXECUTE STATEMENT 2 (7, 'Zoe')",
            "COMMIT"
        ];

        $this->assertSame($expectedLog, $pdo->getLog());
    }
}
