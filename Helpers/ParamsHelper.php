<?php
namespace MauticPlugin\MauticRssToEmailBundle\Helpers;

class ParamsHelper
{
    /**
     * @param string $paramsString
     * @return array<string, string>
     */
    public static function parse($paramsString)
    {
        $params = [];
        preg_match_all('/ ?([^=]+)="([^"]+)"/', $paramsString, $paramMatches);

        if (!empty($paramMatches[1])) {
            foreach ($paramMatches[1] as $index => $paramKey) {
                $params[$paramKey] = $paramMatches[2][$index];
            }
        }

        return $params;
    }

}
