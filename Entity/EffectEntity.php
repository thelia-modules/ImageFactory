<?php
/*************************************************************************************/
/*      This file is part of the module ImageFactory.                                */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/
namespace ImageFactory\Entity;

/**
 * Class EffectEntity
 * @package ImageFactory\Entity
 * @author Gilles Bourgeat <gilles@thelia.net>
 */
class EffectEntity
{
    // effect list
    const EFFECT_NEGATIVE = 'negative';
    const EFFECT_GAMMA = 'gamma';
    const EFFECT_GRAYSCALE = 'grayscale';
    const EFFECT_COLORIZE = 'colorize';
    const EFFECT_BLUR = 'blur';

    /** @var array */
    protected $params = [];

    /** @var string */
    protected $methodName;

    /**
     * EffectEntity constructor.
     * @param string $methodName
     * @param array $params
     */
    public function __construct($methodName, array $params)
    {
        $this->setMethodName($methodName);
        $this->setParams($params);
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @param string $methodName
     * @return EffectEntity
     */
    public function setMethodName($methodName)
    {
        if (!in_array($methodName, [
            self::EFFECT_NEGATIVE,
            self::EFFECT_GAMMA,
            self::EFFECT_GRAYSCALE,
            self::EFFECT_COLORIZE,
            self::EFFECT_BLUR
        ])) {
            throw new \InvalidArgumentException('Invalid argument method name');
        }

        $this->methodName = $methodName;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return EffectEntity
     */
    public function setParams($params)
    {
        switch ($this->getMethodName()) {
            case self::EFFECT_GAMMA:
                if (!isset($params[0]) || (float) $params[0] < 0 || (float) $params[0] > 1) {
                    throw new \InvalidArgumentException('Invalid argument params for effect gamma');
                }
                $params[0] = (float) $params[0];
                break;
            case self::EFFECT_COLORIZE:
                if (!isset($params[0]) || !preg_match($params[0], '/#([a-fA-F0-9]{3}){1,2}\b/')) {
                    throw new \InvalidArgumentException('Invalid argument params for effect colorize');
                }
                break;
            case self::EFFECT_BLUR:
                if (!isset($params[0]) || (float) $params[0] >= 1) {
                    throw new \InvalidArgumentException('Invalid argument params for effect colorize');
                }
                $params[0] = (float) $params[0];
                break;
        }

        $this->params = $params;
        return $this;
    }
}
