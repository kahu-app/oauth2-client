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

  public string $apiDomain = 'https://api.kahu.app';
  public string $domain = 'https://sso.kahu.app';

  protected function fetchResourceOwnerDetails(AccessToken $token): array {
    $response = parent::fetchResourceOwnerDetails($token);

    return $response['data'];
  }

  protected function getDefaultScopes(): array {
    return [
      'user.name',
      'user.email'
    ];
  }

  protected function getScopeSeparator(): string {
    return ' ';
  }

  protected function getPkceMethod(): string {
    return self::PKCE_METHOD_S256;
  }

  // protected function checkResponse(ResponseInterface $response, array $data): void {
  protected function checkResponse(ResponseInterface $response, $data) {
    if ($response->getStatusCode() >= 400) {
      if (is_string($data)) {
        throw KahuIdentityProviderException::clientException($response, []);
      }

      throw KahuIdentityProviderException::clientException($response, $data);
    }

    if (isset($data['error']) === true) {
      throw KahuIdentityProviderException::oauthException($response, $data);
    }
  }

  protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface {
    return new KahuResourceOwner($response);
  }

  public function setRedirectUri(string $redirectUri): void {
    $this->redirectUri = $redirectUri;
  }

  public function getBaseAuthorizationUrl(): string {
    return $this->domain . '/authorization/check';
  }

  public function getBaseAccessTokenUrl(array $params): string {
    return $this->domain . '/authorization/token';
  }

  public function getResourceOwnerDetailsUrl(AccessToken $token): string {
    return $this->domain . '/profile';
  }
}
