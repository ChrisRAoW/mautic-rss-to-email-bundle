<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRssToEmailBundle\Helpers;

class ParamsHelper
{
    /**
     * @return array<string, string>
     */
    public static function parse(string $paramsString): array
    {
        $params = [];
        preg_match_all('/ ?([^=]+)="([^"]+)"/', $paramsString, $paramMatches);

        if (!empty($paramMatches[1])) {
            foreach ($paramMatches[1] as $index => $paramKey) {
                $params[trim($paramKey)] = $paramMatches[2][$index];
            }
        }

        return $params;
    }
}
