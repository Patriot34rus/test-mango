<?php
declare(strict_types=1);

namespace App\Base;

final class Config
{
    public const CONFIG_PART_DB = 'db';

    public function __construct(private readonly array $config){}

    public function getConfig(){
        return $this->config;
    }

    public function getConfigPart(string $partName): array{
        return $this->config[$partName] ?? [];
    }

    public  static function createFromFile(string $configFilePath): self{
        $config = include $configFilePath;
        return new self($config);
    }
}