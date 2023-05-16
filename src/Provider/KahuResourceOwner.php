<?php
declare(strict_types = 1);

namespace Kahu\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class KahuResourceOwner implements ResourceOwnerInterface {
  use ArrayAccessorTrait;

  protected array $response;

  public function __construct(array $response = []) {
    $this->response = $response;
  }

  public function getId(): int|null {
    return $this->getValueByKey($this->response, 'id');
  }

  public function getEmail(): string|null {
    return $this->getValueByKey($this->response, 'email');
  }

  public function getName(): string|null {
    return $this->getValueByKey($this->response, 'name');
  }

  public function toArray(): array {
    return $this->response;
  }
}
