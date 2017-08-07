<?php  defined('C5_EXECUTE') or die('Access denied.');
$form = Loader::helper('form');
$co = new Config();
$co->setPackageObject(Package::getByHandle('fc_google_recaptcha'));
$site_key = $co->get('captcha.site_key', '');
$secret_key = $co->get('captcha.secret_key', '');
$theme = $co->get('captcha.theme', '');
$language = $co->get('captcha.language', '');
$recaptcha_signupUrl = 'https://www.google.com/recaptcha/admin#list';
?>

<div class="clearfix">
	<?php  echo $form->label('site', t('Site Key')); ?>
	<div class="input">
		<?php  echo $form->text('site', is_string($site_key) ? $site_key : '', array('class' => 'span5')); ?>
	</div>
</div>
<div class="clearfix">
	<?php  echo $form->label('secret', t('Secret Key')); ?>
	<div class="input">
		<?php  echo $form->text('secret', is_string($secret_key) ? $secret_key : '', array('class' => 'span5')); ?>
	</div>
</div>

<div class="clearfix">
	<?php  echo $form->label('theme', t('Theme')); ?>
	<div class="input">
		<?php  echo  $form->select('theme', array('light' => t('Light'), 'dark' => t('Dark')), is_string($theme) ? $theme : 'light'); ?>
	</div>
</div>

<div class="clearfix">
	<div class="input">
		<?php  echo $form->label('', t('You can get the public and private keys from <a target="_blank" href="%s">this page</a>.', $recaptcha_signupUrl)); ?>
	</div>
</div>

