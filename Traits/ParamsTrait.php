<?php
namespace MauticPlugin\MauticRssToEmailBundle\Traits;

use MauticPlugin\MauticRssToEmailBundle\Helpers\ParamsHelper;

trait ParamsTrait
{
    /** @var array<string, string> */
    private $params = [];

    /**
     * @param string $paramsString
     * @return array<string, string>
     */
    public function parseParams($paramsString)
    {
        $params = ParamsHelper::parse($paramsString);

        $this->setParams($params);

        return $params;
    }

    /**
     * @param array<string, string> $params
     * @return void
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /** @return array<string, string> */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getParam($key)
    {
        return $this->getParams()[$key] ?? null;
    }
}
