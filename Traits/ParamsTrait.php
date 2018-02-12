<?php
namespace MauticPlugin\MauticRssToEmailBundle\Traits;

use MauticPlugin\MauticRssToEmailBundle\Helpers\ParamsHelper;

trait ParamsTrait
{
    public function parseParams($paramsString)
    {
        $params = ParamsHelper::parse($paramsString);

        $this->setParams($params);

        return $params;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getParam($key)
    {
        if (!is_array($params = $this->getParams()) || !isset($params[$key])) {
            return null;
        }

        return $params[$key];
    }
}
