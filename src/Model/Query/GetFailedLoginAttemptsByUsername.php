<?php declare(strict_types=1);

namespace IntegerNet\PrometheusSecurityMetrics\Model\Query;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Sql\Expression;

class GetFailedLoginAttemptsByUsername
{
    /**
     * @var ResourceConnection
     */
    private $connection;

    public function __construct(ResourceConnection $connection)
    {
        $this->connection = $connection;
    }

    public function execute(): array
    {
        $adapter = $this->connection->getConnection('read');
        $select  = $adapter->select()
            ->from('prometheus_security_metrics_failed_admin_logins', ['username'])
            ->group('username')
            ->columns(['count' => new Expression('COUNT(username)')]);

        return array_map('intval', $adapter->fetchPairs($select));
    }
}
