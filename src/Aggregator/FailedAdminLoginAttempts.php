<?php declare(strict_types=1);

namespace IntegerNet\PrometheusSecurityMetrics\Aggregator;

use IntegerNet\PrometheusSecurityMetrics\Model\Query\GetFailedLoginAttemptsByUsername;
use RunAsRoot\PrometheusExporter\Api\MetricAggregatorInterface;
use RunAsRoot\PrometheusExporter\Service\UpdateMetricService;

class FailedAdminLoginAttempts implements MetricAggregatorInterface
{
    public const METRIC_CODE = 'integer_failed_admin_login_attempts';

    /**
     * @var GetFailedLoginAttemptsByUsername
     */
    private $failedLoginAttemptsByUsername;

    /**
     * @var UpdateMetricService
     */
    private $updateMetricService;

    public function __construct(
        GetFailedLoginAttemptsByUsername $failedLoginAttemptsByUsername,
        UpdateMetricService $updateMetricService
    ) {
        $this->failedLoginAttemptsByUsername = $failedLoginAttemptsByUsername;
        $this->updateMetricService           = $updateMetricService;
    }

    public function getCode(): string
    {
        return self::METRIC_CODE;
    }

    public function getHelp(): string
    {

        return 'Number of failed login attempts in the backend';
    }

    public function getType(): string
    {
        return 'gauge';
    }

    public function aggregate(): bool
    {
        $attempts = $this->failedLoginAttemptsByUsername->execute();

        foreach ($attempts as $username => $count) {
            $this->updateMetricService->update(self::METRIC_CODE, (string)$count, [
                'user' => $username
            ]);
        }

        return true;
    }
}
