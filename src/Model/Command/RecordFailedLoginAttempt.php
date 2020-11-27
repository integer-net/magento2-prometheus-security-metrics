<?php declare(strict_types=1);

namespace IntegerNet\PrometheusSecurityMetrics\Model\Command;

use Magento\Framework\App\ResourceConnection;

class RecordFailedLoginAttempt
{
    /**
     * @var ResourceConnection
     */
    private $connection;

    public function __construct(ResourceConnection $connection)
    {
        $this->connection = $connection;
    }

    public function execute(string $username): void
    {
        $adapter = $this->connection->getConnection('write');

        try {
            $adapter->insert('prometheus_security_metrics_failed_admin_logins', [
                'username' => $username
            ]);
        // @codingStandardsIgnoreLine
        } catch (\Throwable $t) {}
    }
}
