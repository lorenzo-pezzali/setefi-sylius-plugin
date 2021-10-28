<?php

declare(strict_types=1);

namespace Lpweb\SetefiSyliusPlugin\Payum\Action;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Lpweb\SetefiSyliusPlugin\Exception\UnsupportedCurrencyException;
use Lpweb\SetefiSyliusPlugin\Payum\SetefiApi;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Sylius\Component\Core\Model\PaymentInterface as SyliusPaymentInterface;
use Payum\Core\Request\Capture;

final class CaptureAction implements ActionInterface, ApiAwareInterface {

    /** @var Client */
    private $client;

    /** @var SetefiApi */
    private $api;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    /**
     * @param bool $sandbox
     *
     * @return string
     */
    private function getEndpoint(bool $sandbox): string {
//        if ($sandbox) {
//            return 'https://test.monetaonline.it/monetaweb/payment/2/xml';
//        }
//
//        return 'https://www.monetaonline.it/monetaweb/payment/2/xml';
        return 'https://sylius-payment.free.beeceptor.com';
    }

    /**
     * @param mixed $request
     *
     * @throws UnsupportedCurrencyException
     * @throws GuzzleException
     */
    public function execute($request): void {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var SyliusPaymentInterface $payment */
        $payment = $request->getModel();

        try {
            $response = $this->client->request('POST', $this->getEndpoint($this->api->isSandbox()), [
                'content-type' => 'application/x-www-form-urlencoded',
                'body'         => [
                    'id'              => $this->api->getId(),
                    'password'        => $this->api->getPassword(),
                    'operationType'   => 'pay',
                    'amount'          => $payment->getAmount(),
                    'currencycode'    => $this->getCurrencyCode($payment->getCurrencyCode()),
                    'merchantOrderId' => $payment->getOrder()->getId(),
                    'description'
                ],
            ]);
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
        } finally {
            $payment->setDetails(['status' => $response->getStatusCode()]);
        }
    }

    /**
     * @param string $orderCurrency
     *
     * @return string
     * @throws UnsupportedCurrencyException
     */
    private function getCurrencyCode(string $orderCurrency): string {
        switch ($orderCurrency) {
            case "EUR":
                return '978';
            case "CHF":
                return '756';
            case "GBP":
                return '826';
            case "USD":
                return '840';
        }

        throw new UnsupportedCurrencyException(sprintf("Currency %s is not supported by Setefi", $orderCurrency));
    }

    public function supports($request): bool {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof SyliusPaymentInterface;
    }

    public function setApi($api): void {
        if (!$api instanceof SetefiApi) {
            throw new UnsupportedApiException('Not supported. Expected an instance of ' . SetefiApi::class);
        }

        $this->api = $api;
    }

}