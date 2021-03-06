<?php
/**
 * Copyright (c) 2015 Lukas Reschke lukas@owncloud.com
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */


namespace OC\AppFramework\Http;

use OCP\AppFramework\Http;
use OCP\AppFramework\Http\ContentSecurityPolicy;

/**
 * Class ContentSecurityPolicyTest
 *
 * @package OC\AppFramework\Http
 */
class ContentSecurityPolicyTest extends \Test\TestCase {

	/** @var ContentSecurityPolicy */
	private $contentSecurityPolicy;

	public function setUp() {
		parent::setUp();
		$this->contentSecurityPolicy = new ContentSecurityPolicy();
	}

	public function testGetPolicyDefault() {
		$defaultPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self';media-src 'self'";
		$this->assertSame($defaultPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyScriptDomainValid() {
		$expectedPolicy = "default-src 'none';script-src 'self' www.owncloud.com 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self';media-src 'self'";

		$this->contentSecurityPolicy->addAllowedScriptDomain('www.owncloud.com');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyScriptDomainValidMultiple() {
		$expectedPolicy = "default-src 'none';script-src 'self' www.owncloud.com www.owncloud.org 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self';media-src 'self'";

		$this->contentSecurityPolicy->addAllowedScriptDomain('www.owncloud.com');
		$this->contentSecurityPolicy->addAllowedScriptDomain('www.owncloud.org');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyScriptAllowInline() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-inline' 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self';media-src 'self'";

		$this->contentSecurityPolicy->allowInlineScript(true);
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyScriptAllowInlineWithDomain() {
		$expectedPolicy = "default-src 'none';script-src 'self' www.owncloud.com 'unsafe-inline' 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self';media-src 'self'";

		$this->contentSecurityPolicy->addAllowedScriptDomain('www.owncloud.com');
		$this->contentSecurityPolicy->allowInlineScript(true);
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyScriptDisallowInlineAndEval() {
		$expectedPolicy = "default-src 'none';script-src 'self';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self';media-src 'self'";

		$this->contentSecurityPolicy->allowInlineScript(false);
		$this->contentSecurityPolicy->allowEvalScript(false);
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyStyleDomainValid() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' www.owncloud.com 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self';media-src 'self'";

		$this->contentSecurityPolicy->addAllowedStyleDomain('www.owncloud.com');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyStyleDomainValidMultiple() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' www.owncloud.com www.owncloud.org 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self';media-src 'self'";

		$this->contentSecurityPolicy->addAllowedStyleDomain('www.owncloud.com');
		$this->contentSecurityPolicy->addAllowedStyleDomain('www.owncloud.org');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyStyleAllowInline() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self';media-src 'self'";

		$this->contentSecurityPolicy->allowInlineStyle(true);
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyStyleAllowInlineWithDomain() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' www.owncloud.com 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self';media-src 'self'";

		$this->contentSecurityPolicy->addAllowedStyleDomain('www.owncloud.com');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyStyleDisallowInline() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self';img-src 'self';font-src 'self';connect-src 'self';media-src 'self'";

		$this->contentSecurityPolicy->allowInlineStyle(false);
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyImageDomainValid() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self' www.owncloud.com;font-src 'self';connect-src 'self';media-src 'self'";

		$this->contentSecurityPolicy->addAllowedImageDomain('www.owncloud.com');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyImageDomainValidMultiple() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self' www.owncloud.com www.owncloud.org;font-src 'self';connect-src 'self';media-src 'self'";

		$this->contentSecurityPolicy->addAllowedImageDomain('www.owncloud.com');
		$this->contentSecurityPolicy->addAllowedImageDomain('www.owncloud.org');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyFontDomainValid() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self' www.owncloud.com;connect-src 'self';media-src 'self'";

		$this->contentSecurityPolicy->addAllowedFontDomain('www.owncloud.com');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyFontDomainValidMultiple() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self' www.owncloud.com www.owncloud.org;connect-src 'self';media-src 'self'";

		$this->contentSecurityPolicy->addAllowedFontDomain('www.owncloud.com');
		$this->contentSecurityPolicy->addAllowedFontDomain('www.owncloud.org');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyConnectDomainValid() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self' www.owncloud.com;media-src 'self'";

		$this->contentSecurityPolicy->addAllowedConnectDomain('www.owncloud.com');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyConnectDomainValidMultiple() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self' www.owncloud.com www.owncloud.org;media-src 'self'";

		$this->contentSecurityPolicy->addAllowedConnectDomain('www.owncloud.com');
		$this->contentSecurityPolicy->addAllowedConnectDomain('www.owncloud.org');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyMediaDomainValid() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self';media-src 'self' www.owncloud.com";

		$this->contentSecurityPolicy->addAllowedMediaDomain('www.owncloud.com');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyMediaDomainValidMultiple() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self';media-src 'self' www.owncloud.com www.owncloud.org";

		$this->contentSecurityPolicy->addAllowedMediaDomain('www.owncloud.com');
		$this->contentSecurityPolicy->addAllowedMediaDomain('www.owncloud.org');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyObjectDomainValid() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self';media-src 'self';object-src www.owncloud.com";

		$this->contentSecurityPolicy->addAllowedObjectDomain('www.owncloud.com');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyObjectDomainValidMultiple() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self';media-src 'self';object-src www.owncloud.com www.owncloud.org";

		$this->contentSecurityPolicy->addAllowedObjectDomain('www.owncloud.com');
		$this->contentSecurityPolicy->addAllowedObjectDomain('www.owncloud.org');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}


	public function testGetAllowedFrameDomain() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self';media-src 'self';frame-src www.owncloud.com";

		$this->contentSecurityPolicy->addAllowedFrameDomain('www.owncloud.com');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testGetPolicyFrameDomainValidMultiple() {
		$expectedPolicy = "default-src 'none';script-src 'self' 'unsafe-eval';style-src 'self' 'unsafe-inline';img-src 'self';font-src 'self';connect-src 'self';media-src 'self';frame-src www.owncloud.com www.owncloud.org";

		$this->contentSecurityPolicy->addAllowedFrameDomain('www.owncloud.com');
		$this->contentSecurityPolicy->addAllowedFrameDomain('www.owncloud.org');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}

	public function testConfigureStacked() {
		$expectedPolicy = "default-src 'none';script-src 'self' script.owncloud.org;style-src 'self' style.owncloud.org;img-src 'self' img.owncloud.org;font-src 'self' font.owncloud.org;connect-src 'self' connect.owncloud.org;media-src 'self' media.owncloud.org;object-src objects.owncloud.org;frame-src frame.owncloud.org";

		$this->contentSecurityPolicy->allowInlineStyle(false)
			->allowEvalScript(false)
			->addAllowedScriptDomain('script.owncloud.org')
			->addAllowedStyleDomain('style.owncloud.org')
			->addAllowedFontDomain('font.owncloud.org')
			->addAllowedImageDomain('img.owncloud.org')
			->addAllowedConnectDomain('connect.owncloud.org')
			->addAllowedMediaDomain('media.owncloud.org')
			->addAllowedObjectDomain('objects.owncloud.org')
			->addAllowedFrameDomain('frame.owncloud.org');
		$this->assertSame($expectedPolicy, $this->contentSecurityPolicy->buildPolicy());
	}
}
