<?php declare(strict_types=1);

namespace IntegerNet\PrometheusSecurityMetrics\Observer\Backend;

use IntegerNet\PrometheusSecurityMetrics\Model\Command\RecordFailedLoginAttempt;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class TrackFailedLogin implements ObserverInterface
{
    /**
     * @var RecordFailedLoginAttempt
     */
    private $recordFailedLoginAttempt;

    public function __construct(RecordFailedLoginAttempt $recordFailedLoginAttempt)
    {
        $this->recordFailedLoginAttempt = $recordFailedLoginAttempt;
    }

    public function execute(Observer $observer): void
    {
        if ($this->getResult($observer) === false) {
            $this->recordFailedLoginAttempt->execute(
                $this->getUsername($observer)
            );
        }
    }

    private function getUsername(Observer $observer): string
    {
        return (string)$observer->getEvent()->getData('username');
    }

    private function getResult(Observer $observer): bool
    {
        return filter_var($observer->getEvent()->getData('result'), FILTER_VALIDATE_BOOL);
    }
}
