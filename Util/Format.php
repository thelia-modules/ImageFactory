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

namespace ImageFactory\Util;

/**
 * Class Format
 * @package ImageFactory\Util
 * @author Gilles Bourgeat <gilles@thelia.net>
 */
class Format
{
    public static function normalizePath($path)
    {
        return (DIRECTORY_SEPARATOR === '\\')
            ? str_replace('/', '\\', $path)
            : str_replace('\\', '/', $path);
    }
}
