<?php

declare(strict_types=1);

namespace Square\Models;

/**
 * Defines the fields that are included in the response body of
 * a request to the `CreateOrder` endpoint.
 *
 * Either `errors` or `order` is present in a given response, but never both.
 */
class CreateOrderResponse implements \JsonSerializable
{
    /**
     * @var Order|null
     */
    private $order;

    /**
     * @var Error[]|null
     */
    private $errors;

    /**
     * Returns Order.
     *
     * Contains all information related to a single order to process with Square,
     * including line items that specify the products to purchase. `Order` objects also
     * include information about any associated tenders, refunds, and returns.
     *
     * All Connect V2 Transactions have all been converted to Orders including all associated
     * itemization data.
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * Sets Order.
     *
     * Contains all information related to a single order to process with Square,
     * including line items that specify the products to purchase. `Order` objects also
     * include information about any associated tenders, refunds, and returns.
     *
     * All Connect V2 Transactions have all been converted to Orders including all associated
     * itemization data.
     *
     * @maps order
     */
    public function setOrder(?Order $order): void
    {
        $this->order = $order;
    }

    /**
     * Returns Errors.
     *
     * Any errors that occurred during the request.
     *
     * @return Error[]|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    /**
     * Sets Errors.
     *
     * Any errors that occurred during the request.
     *
     * @maps errors
     *
     * @param Error[]|null $errors
     */
    public function setErrors(?array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * Encode this object to JSON
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        $json = [];
        $json['order']  = $this->order;
        $json['errors'] = $this->errors;

        return array_filter($json, function ($val) {
            return $val !== null;
        });
    }
}
