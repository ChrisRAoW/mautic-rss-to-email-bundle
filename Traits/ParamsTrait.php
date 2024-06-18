<?php
namespace MauticPlugin\MauticRssToEmailBundle\Traits;

use MauticPlugin\MauticRssToEmailBundle\Helpers\ParamsHelper;

trait ParamsTrait
{
    /** @var string[] */
    private $params = [];

    /**
     * @param string $paramsString
     * @return string[]
     */
    public function parseParams($paramsString)
    {
        $params = ParamsHelper::parse($paramsString);

        $this->setParams($params);

        return $params;
    }

    /**
     * @param string[] $params
     * @return void
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /** @return string[] */
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
