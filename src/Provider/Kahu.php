<?php
declare(strict_types = 1);

namespace Kahu\OAuth2\Client\Provider;

use Kahu\OAuth2\Client\Provider\Exception\KahuIdentityProviderException;
use League\OAuth2\Client\Provider\AbstractProvider ;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Kahu extends AbstractProvider {
  use BearerAuthorizationTrait;

  public string $domain = 'https://sso.kahu.app';
  public string $apiDomain = 'https://api.kahu.app';

  protected function fetchResourceOwnerDetails(AccessToken $token): array {
    return [];
  }

  protected function getDefaultScopes(): array {
    return [
      'user.email' // ??
    ];
  }

  protected function checkResponse(ResponseInterface $response, array $data): void {
    if ($response->getStatusCode() >= 400) {
      throw KahuIdentityProviderException::clientException($response, $data);
    }

    if (isset($data['error']) === true) {
      throw KahuIdentityProviderException::oauthException($response, $data);
    }
  }

  protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface {
    return new KahuResourceOwner($response);
  }

  public function getBaseAuthorizationUrl(): string {
    return $this->domain . '/xpto';
  }

  public function getBaseAccessTokenUrl(array $params): string {
    return $this->domain . '/something';
  }

  public function getResourceOwnerDetailsUrl(AccessToken $token): string {
    return $this->domain . '/v1/user';
  }
}
