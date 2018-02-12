<?php
namespace MauticPlugin\MauticRssToEmailBundle\Helpers;

class ParamsHelper
{
    public static function parse($paramsString)
    {
        $params = [];
        preg_match_all('/ ?([^=]+)="([^"]+)"/', $paramsString, $paramMatches);

        if (!empty($paramMatches[1])) {
            foreach ($paramMatches[1] as $index => $paramKey) {
                $key   = $paramKey;
                $value = $paramMatches[2][$index];

                $params[$key] = $value;
            }
        }

        return $params;
    }

}
