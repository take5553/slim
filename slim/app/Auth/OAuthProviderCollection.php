<?php
declare(strict_types=1);

namespace App\Auth;

use League\OAuth2\Client\Provider\AbstractProvider;

// 配列のみを担当するクラス（いわゆるファーストクラスコレクション）
final class OAuthProviderCollection
{
    /** @param array<string, AbstractProvider> $providers */
    public function __construct(
        private readonly array $providers
    ) { }

    public function get(string $name): ?AbstractProvider
    {
        return $this->providers[$name] ?? null;
    }
}
