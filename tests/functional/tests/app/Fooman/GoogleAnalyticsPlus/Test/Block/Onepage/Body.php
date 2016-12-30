<?php
/**
 * @author     Kristof Ringleff
 * @copyright  Copyright (c) 2015 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Fooman\GoogleAnalyticsPlus\Test\Block\Onepage;

use Magento\Mtf\Block\Block;

class Body extends Block
{

    const GA_MARKER_START = '<!-- BEGIN GOOGLE ANALYTICS CODE -->';
    const GA_MARKER_END = '<!-- END GOOGLE ANALYTICS CODE -->';

    public function getGaScript()
    {
        $body = $this->_rootElement->getAttribute('innerHTML');
        $start = strpos($body, self::GA_MARKER_START);
        $end = strpos($body, self::GA_MARKER_END) + strlen(self::GA_MARKER_END);
        return PHP_EOL . substr($body, $start, $end - $start) . PHP_EOL;
    }
}

