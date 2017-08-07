<?php  defined('C5_EXECUTE') or die('Access denied.');

class FcGoogleRecaptchaPackage extends Package {

	protected $pkgHandle = 'fc_google_recaptcha';
	protected $appVersionRequired = '5.6';
	protected $pkgVersion = '1.0';

	public function getPackageName() {
		return t('Google reCAPTCHA');
	}

	public function getPackageDescription() {
		return t('Use Google reCAPTCHA.');
	}

	public function install() {
		$pkg = parent::install();
		$this->installOrUpgrade($pkg, '0.0.0');
	}

	public function upgrade() {
		$currentVersion = $this->getPackageVersion();
		parent::upgrade();
		$this->installOrUpgrade($this, $currentVersion);
	}

	private function installOrUpgrade($pkg, $upgradeFromVersion) {
		$currentLocale = Localization::activeLocale();
		if ($currentLocale != 'en_US') {
			Localization::changeLocale('en_US');
		}
		Loader::model('system/captcha/library');
		if(!SystemCaptchaLibrary::getByHandle('fc_google_recaptcha')) {
			SystemCaptchaLibrary::add('fc_google_recaptcha', t('Google reCAPTCHA'), $pkg);
		}
		if ($currentLocale != 'en_US') {
			Localization::changeLocale($currentLocale);
		}
	}

	public function uninstall() {
		Loader::model('system/captcha/library');
		$active = SystemCaptchaLibrary::getActive();
		if($active && ($active->getSystemCaptchaLibraryHandle() == 'fc_google_recaptcha')) {
			foreach(SystemCaptchaLibrary::getList() as $anotherCaptcha) {
				if($anotherCaptcha->getSystemCaptchaLibraryHandle() != 'fc_google_recaptcha') {
					$anotherCaptcha->activate();
					break;
				}
			}
		}
		parent::uninstall();
	}
	
	
	public function on_start(){
		Events::extend('on_before_render', 'FcGoogleRecaptchaPackage', 'on_before_render_handler', DIR_PACKAGES.'/fc_google_recaptcha/controller.php');
	}
	
	public function on_before_render_handler(){
		$html = Loader::helper('html');
		$view = View::getInstance();
		$assetUrl = 'https://www.google.com/recaptcha/api.js?onload=fcRecaptcha&render=explicit';
		$view->addHeaderItem('<script type="text/javascript" src="' . $assetUrl . '"></script>');
		$view->addHeaderItem($html->javascript('render.js', 'fc_google_recaptcha'));
	}
}
