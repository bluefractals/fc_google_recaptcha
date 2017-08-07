<?php  defined('C5_EXECUTE') or die('Access denied.');

class FcGoogleRecaptchaSystemCaptchaTypeController extends SystemCaptchaTypeController {

	function label() {
		return '';
	}
	
	function display() {
		return '';
	}

	function showInput() {
		$co = new Config();
		$co->setPackageObject(Package::getByHandle('fc_google_recaptcha'));
	
		echo '<div id="' . uniqid('ecr') . '" class="g-recaptcha fcRecaptcha" data-sitekey="' . $co->get('captcha.site_key') . '" data-theme="' . $co->get('captcha.theme') . '"></div>';
		echo '<noscript>
          <div style="width: 302px; height: 352px;">
            <div style="width: 302px; height: 352px; position: relative;">
              <div style="width: 302px; height: 352px; position: absolute;">
                <iframe src="https://www.google.com/recaptcha/api/fallback?k=' . $co->get('captcha.site_key') . '"
                        frameborder="0" scrolling="no"
                        style="width: 302px; height:352px; border-style: none;">
                </iframe>
              </div>
              <div style="width: 250px; height: 80px; position: absolute; border-style: none;
                          bottom: 21px; left: 25px; margin: 0; padding: 0; right: 25px;">
                <textarea id="g-recaptcha-response" name="g-recaptcha-response"
                          class="g-recaptcha-response"
                          style="width: 250px; height: 80px; border: 1px solid #c1c1c1;
                                 margin: 0; padding: 0; resize: none;" value=""></textarea>
              </div>
            </div>
          </div>
        </noscript>';
		
		// Make it responsive
		echo '<style type="text/css">
				@media (max-width: 340px) {
					.g-recaptcha > div > div { width: auto!important; }
					.g-recaptcha iframe { width: 100%!important; }
					.g-recaptcha {
					    transform: scale(0.95);
					    transform-origin: 0 0 0;
					}
				}
			</style>';
	}

	/**
	 * Verifies the captcha submission
	 * @return bool
	 */
	public function check() {
		$co = new Config();
		$co->setPackageObject(Package::getByHandle('fc_google_recaptcha'));
		$iph = Loader::helper('validation/ip');
	
		$qsa = http_build_query(
				array(
						'secret' => $co->get('captcha.secret_key'),
						'response' => $_REQUEST['g-recaptcha-response'],
						'remoteip' => $iph->getRequestIP()
				)
				);
		
		$ch = curl_init('https://www.google.com/recaptcha/api/siteverify?' . $qsa);
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // false while testing in localhost
	
		$response = curl_exec($ch);
		if ($response !== false) {
			$data = json_decode($response, true);
			return $data['success'];
		} else {
			return false;
		}
	}

	public function saveOptions($data) {
		$co = new Config();
		$co->setPackageObject(Package::getByHandle('fc_google_recaptcha'));
		$co->save('captcha.site_key', $data['site']);
		$co->save('captcha.secret_key', $data['secret']);
		$co->save('captcha.theme', $data['theme']);
		$co->save('captcha.language', $data['language']);
	}
}
