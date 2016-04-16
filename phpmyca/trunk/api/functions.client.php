<?
/**
 * phpmyca - client certificates functions
 * @package    phpmyca
 * @author     Mike Green <mdgreen@gmail.com>
 * @copyright  Copyright (c) 2010, Mike Green
 * @license    http://opensource.org/licenses/gpl-2.0.php GPLv2
 */
(basename($_SERVER['PHP_SELF']) == basename(__FILE__)) && die('Access Denied');

/**
 * Process requests to add a client certificate
 */
function getPageAdd() {
	global $_WA;
	$_WA->html->setPageTitle('Создать клиентский сертификат');
	// If they have not submitted yet, dump them to the form.
	if ($_WA->html->getRequestVar(WA_QS_CONFIRM) !== 'yes') {
		die($_WA->html->loadTemplate('client.add.php'));
		}
	$rc = $_WA->actionClientAdd();
	if (!($rc === true)) {
		$_WA->html->errorMsgSet($rc);
		die($_WA->html->loadTemplate('client.add.php'));
		}
	// Success ;)
	$_WA->html->setPageTitle('Результат добавления сертификата');
	$qs = $_WA->html->getMenuQs(MENU_CERTS_CLIENT);
	$_WA->html->addMenuLink($qs,'Return','greenoutline');
	$h   = array();
	$h[] = $_WA->html->getPageHeader();
	$h[] = 'Сертификат добавлен успешно.';
	$h[]  = $_WA->html->getPageFooter();
	die(implode("\n",$h) . "\n");
	}

/**
 * Process requests to generate a client certificate from a CSR.
 * @return void
 */
function getPageCsrSign() {
	global $_WA;
	$_WA->html->setPageTitle('Создание сертификата на основе запроса CSR');
	$conf = (isset($_POST[WA_QS_CONFIRM]))    ? $_POST[WA_QS_CONFIRM]  : false;
	$csr  = (isset($_POST['csr']))            ? $_POST['csr']          : false;
	//
	// Have they provided a valid csr yet?
	//
	if (!is_string($csr) or strlen($csr) < 1) {
		die($_WA->html->loadTemplate('get.csr.php'));
		}
	//
	// Validate the csr
	//
	$info = openssl_csr_get_subject($csr,false);
	if (!is_array($info) or !isset($info['commonName'])) {
		$_WA->html->errorMsgSet('Некорректный CSR');
		die($_WA->html->loadTemplate('get.csr.php'));
		}
	//
	// Fields required for the next phase...
	//
	$caId   = (isset($_POST['caId'])) ? $_POST['caId'] : false;
	$days   = (isset($_POST['Days'])) ? $_POST['Days'] : false;
	$test1  = (is_numeric($caId) and $caId > 0);
	$test2  = (is_numeric($days) and $days > 0);
	if (!$test1 or !$test2) {
		die($_WA->html->loadTemplate('client.sign.php'));
		}
	$rc = $_WA->actionClientCsrSign();
	if (!($rc === true)) {
		$_WA->html->errorMsgSet($rc);
		die($_WA->html->loadTemplate('client.sign.php'));
		}
	// Success ;)
	$_WA->html->setPageTitle('Результат подписи сертификата');
	$qs = $_WA->html->getMenuQs(MENU_CERTS_CLIENT);
	$_WA->html->addMenuLink($qs,'Вернуться','greenoutline');
	$h   = array();
	$h[] = $_WA->html->getPageHeader();
	$h[] = 'Сертификат создан и импортирован успешно';
	$h[]  = $_WA->html->getPageFooter();
	die(implode("\n",$h) . "\n");
	}

/**
 * Process requests to import a client certificate.
 * @return void
 */
function getPageImport() {
	global $_WA;
	$_WA->html->setPageTitle('Импорт клиентского сертификата');
	$conf = (isset($_POST[WA_QS_CONFIRM])) ? $_POST[WA_QS_CONFIRM] : false;
	$pem  = $_WA->html->parseCertificate('cert_file','cert');
	$key  = $_WA->html->parsePrivateKey('key_file','key');
	$pass = (isset($_POST['pass'])) ? $_POST['pass'] : false;
	$csr  = $_WA->html->parseCertificateRequest('csr_file','csr');
	$test = ($pem === false || $key === false) ? false : true;
	if ($conf !== 'yes' or $test === false) {
		die($_WA->html->loadTemplate('client.import.php'));
		}
	$rc = $_WA->actionClientImport($pem,$key,$pass,$csr);
	if (!($rc === true)) {
		$_WA->html->errorMsgSet($rc);
		die($_WA->html->loadTemplate('client.import.php'));
		}
	// Success ;)
	$_WA->html->setPageTitle('Результат импорта сертификата');
	$qs = $_WA->html->getActionQs(WA_ACTION_CLIENT_IMPORT);
	$_WA->html->addMenuLink($qs,'Импортировать следующий','greenoutline');
	$qs = $_WA->html->getMenuQs(MENU_CERTS_CLIENT);
	$_WA->html->addMenuLink($qs,'Вернуться','greenoutline');
	$h   = array();
	$h[] = $_WA->html->getPageHeader();
	$h[] = 'Сертификат успешно импортирован';
	$h[]  = $_WA->html->getPageFooter();
	die(implode("\n",$h) . "\n");
	}

?>
