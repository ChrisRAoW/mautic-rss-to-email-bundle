<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRssToEmailBundle\Traits;

use MauticPlugin\MauticRssToEmailBundle\Helpers\ParamsHelper;

trait ParamsTrait
{
    /**
     * @var array<string, string>
     */
    protected array $params = [];

    /**
     * @return array<string, string>
     */
    public function parseParams(string $paramsString): array
    {
        $params = ParamsHelper::parse($paramsString);

        $this->setParams($params);

        return $params;
    }

    /**
     * @param array<string, string> $params
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /**
     * @return array<string, string>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    public function getParam(string $key): ?string
    {
        return $this->params[$key] ?? null;
    }
}
