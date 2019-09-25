<?php
namespace Fooman\GoogleAnalyticsPlus\UnitTest\Plugin;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Fooman\PhpunitBridge\BaseUnitTestCase;

class GaTest extends BaseUnitTestCase
{
    const TEST_ACCT_ID = 'UA-123456';
    const TEST_PAGE_URL = '/cms/index';

    protected $gaMock;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);
    }

    public function testAroundGetPageTrackingCodeIncludesCreateTracker()
    {
        $actualResult = $this->executeAroundGetPageTracking();
        $this->assertEquals(self::TEST_ACCT_ID, $actualResult['accountId']);
    }

    public function testAroundGetPageTrackingCodeIncludesSetPageName()
    {
        $actualResult = $this->executeAroundGetPageTracking();
        $this->assertEquals('/cms', trim($actualResult['optPageUrl']));
    }

    private function prepareSubjectMock()
    {
        $this->gaMock = $this->createPartialMock(
            \Magento\GoogleAnalytics\Block\Ga::class,
            ['escapeUrl', 'getPageName']
        );

        $this->gaMock->expects($this->any())->method('escapeUrl')->will($this->returnArgument(0));
        $this->gaMock->expects($this->any())->method('getPageName')->will($this->returnValue(self::TEST_PAGE_URL));
    }

    /**
     * @return array
     */
    private function executeAroundGetPageTracking()
    {
        $this->prepareSubjectMock();

        /** @var $helper \Fooman\GoogleAnalyticsPlus\Helper\Url */
        $helper = $this->objectManager->getObject(
            \Fooman\GoogleAnalyticsPlus\Helper\Url::class
        );

        /** @var $plugin \Fooman\GoogleAnalyticsPlus\Plugin\Ga */
        $plugin = $this->objectManager->getObject(
            \Fooman\GoogleAnalyticsPlus\Plugin\Ga::class,
            ['urlHelper' => $helper]
        );

        $standardResult = ['accountId' => self::TEST_ACCT_ID, 'isAnonymizedIpActive' => 0, 'optPageUrl' => ''];
        return $plugin->afterGetPageTrackingData($this->gaMock, $standardResult);
    }
}
