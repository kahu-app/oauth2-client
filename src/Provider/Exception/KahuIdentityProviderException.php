<?php
declare(strict_types = 1);

namespace Kahu\OAuth2\Client\Provider\Exception;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Http\Message\ResponseInterface;

class KahuIdentityProviderException extends IdentityProviderException {
  protected static function fromResponse(ResponseInterface $response, string $message = null): IdentityProviderException {
    return new static($message, $response->getStatusCode(), (string)$response->getBody());
  }

  public static function clientException(ResponseInterface $response, array $data): IdentityProviderException {
    return static::fromResponse(
      $response,
      $data['message'] ?? $response->getReasonPhrase()
    );
  }

  public static function oauthException(ResponseInterface $response, array $data): IdentityProviderException {
    return static::fromResponse(
      $response,
      $data['error'] ?? $response->getReasonPhrase()
    );
  }
}
