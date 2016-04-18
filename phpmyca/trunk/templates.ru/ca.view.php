<?
/**
 * @package    phpmyca
 * @author     Mike Green <mdgreen@gmail.com>
 * @copyright  Copyright (c) 2010, Mike Green
 * @license    http://opensource.org/licenses/gpl-2.0.php GPLv2
 */
(basename($_SERVER['PHP_SELF']) == basename(__FILE__)) && die('Access Denied');

$cert =& $this->getVar('cert');
if (!($cert instanceof phpmycaCert)) {
	$m = 'Certificate data is missing, cannot continue.';
	die($this->getPageError($m));
	}
$issuer =& $this->getVar('issuer');
if (!($issuer instanceof phpmycaCert)) {
	$m = 'Issuer cert is missing, cannot continue.';
	die($this->getPageError($m));
	}
$signedCaCerts     =& $this->getVar('signedCaCerts');
$signedClientCerts =& $this->getVar('signedClientCerts');
$signedServerCerts =& $this->getVar('signedServerCerts');
$qs_back        = $this->getActionQs(WA_ACTION_CA_LIST);
$qs_pkcs12      = $this->getActionQs(WA_ACTION_CA_PKCS12);
$qs_issuer      = $this->getMenuQs(MENU_CERTS_CA)
                . '&' . WA_QS_ACTION . '=' . WA_ACTION_CA_VIEW
                . '&' . WA_QS_ID . '=' . $cert->ParentId;
$qs_ca_cert     = $this->getMenuQs(MENU_CERTS_CA)
                . '&' . WA_QS_ACTION . '=' . WA_ACTION_CA_VIEW
                . '&' . WA_QS_ID . '=';
$qs_client_cert = $this->getMenuQs(MENU_CERTS_CLIENT)
                . '&' . WA_QS_ACTION . '=' . WA_ACTION_CLIENT_VIEW
                . '&' . WA_QS_ID . '=';
$qs_server_cert = $this->getMenuQs(MENU_CERTS_SERVER)
                . '&' . WA_QS_ACTION . '=' . WA_ACTION_SERVER_VIEW
                . '&' . WA_QS_ID . '=';
$qs_bundle      = $this->getActionQs(WA_ACTION_BUNDLE);
$qs_revoke      = $this->getActionQs(WA_ACTION_CA_REVOKE);

// import cert links
$qs_import_pem = $this->getActionQs(WA_ACTION_BROWSER_IMPORT);

// expired or revoked?
$expired = ($cert->isExpired());
$revoked = ($cert->isRevoked());
// set class for expired
$expireClass = '';
if (!$expired and !$revoked) {
	if ($cert->isExpired(30)) {
		$expireClass = ' class="expire30"';
		} elseif ($cert->isExpired(60)) {
		$expireClass = ' class="expire60"';
		} elseif ($cert->isExpired(90)) {
		$expireClass = ' class="expire90"';
		}
	}

// self signed?
$isSelfSigned = ($cert->FingerprintMD5 == $issuer->FingerprintMD5);

// footer links
if (!$expired and !$revoked) {
	if ($cert->isRevokable()) {
		$this->addMenuLink($qs_revoke,'Отозвать','redoutline');
		}
	if ($cert->hasPrivateKey()) {
		if ($cert->isEncrypted()) {
			$qs = $this->getActionQs(WA_ACTION_CHANGE_PASS);
			$this->addMenuLink($qs,'Изменить пароль прив. ключа','greenoutline');
			$qs = $this->getActionQs(WA_ACTION_DECRYPT);
			$this->addMenuLink($qs,'Расшифровать прив. ключ','greenoutline');
			} else {
			$qs = $this->getActionQs(WA_ACTION_ENCRYPT);
			$this->addMenuLink($qs,'Зашифровать прив. ключ','greenoutline');
			}
		}
	if ($cert->ParentId > 0) {
		$this->addMenuLink($qs_bundle,'Скачать PEM УЦ','greenoutline');
		}
	$this->addMenuLink($qs_pkcs12,'Скачать PKCS12','greenoutline');
	$this->addMenuLink($qs_import_pem,'Импорт в браузер','greenoutline');
	}
$this->addMenuLink($qs_back,'Назад','greenoutline');
?>
<?= $this->getPageHeader(); ?>
<TABLE ALIGN="center">
	<TR>
		<TH>ID Сертификата</TH>
		<TD>
			<?= $cert->Id . "\n"; ?>
		</TD>
	</TR>
	<TR>
		<TH>Описание</TH>
		<TD>
			<?= $cert->Description . "\n"; ?>
		</TD>
	</TR>
<? if ($revoked) { ?>
	<TR>
		<TH>Дата отзыва</TH>
		<TD>
			<?= $cert->RevokeDate; ?>
		</TD>
	</TR>
<? } else { ?>
	<TR>
		<TH>Срок действия</TH>
		<TD<?= $expireClass; ?>>
			<?= $cert->ValidFrom . ' до ' . $cert->ValidTo . "\n"; ?>
		</TD>
	</TR>
<? } ?>
	<TR>
		<TH COLSPAN="2">Contact Information</TH>
	</TR>
<? if ($cert->CommonName) { ?>
	<TR>
		<TH>commonName</TH>
		<TD><?= $cert->CommonName; ?></TD>
	</TR>
<? } ?>
<? if ($cert->OrgName) { ?>
	<TR>
		<TH>Организация</TH>
		<TD><?= $cert->OrgName; ?></TD>
	</TR>
<? } ?>
<? if ($cert->OrgUnitName) { ?>
	<TR>
		<TH>Отдел</TH>
		<TD><?= nl2br($cert->OrgUnitName); ?></TD>
	</TR>
<? } ?>
<? if ($cert->LocalityName) { ?>
	<TR>
		<TH>Населенный пункт</TH>
		<TD><?= nl2br($cert->LocalityName); ?></TD>
	</TR>
<? } ?>
<? if ($cert->CountryName) { ?>
	<TR>
		<TH>Страна</TH>
		<TD><?= $cert->CountryName; ?></TD>
	</TR>
<? } ?>
	<TR>
		<TH COLSPAN="2">Отпечатки/Контрольные суммы</TH>
	</TR>
	<TR>
		<TH>MD5</TH>
		<TD>
			<?= $cert->FingerprintMD5 . "\n"; ?>
		</TD>
	</TR>
	<TR>
		<TH>SHA1</TH>
		<TD>
			<?= $cert->FingerprintSHA1 . "\n"; ?>
		</TD>
	</TR>
	<TR>
		<TH>Серийный номер</TH>
		<TD>
			<?= $cert->SerialNumber . "\n"; ?>
		</TD>
	</TR>
	<TR>
		<TH>Последний изданный серийный номер</TH>
		<TD>
			<?= $cert->SerialLastIssued . "\n"; ?>
		</TD>
	</TR>
	<TR>
		<TH>Создан</TH>
		<TD>
			<?= $cert->CreateDate . "\n"; ?>
		</TD>
	</TR>
<? if ($isSelfSigned) { ?>
	<TR>
		<TH>Издатель</TH>
		<TD>
			Самоподписываемый
		</TD>
	</TR>
<? } ?>
</TABLE>
<? if (!$isSelfSigned) {
$id  = 'tog_' . $this->getNumber();
$hr = '<A HREF="javascript:void(0)" ONCLICK="toggleDisplay(\'' . $id . '\')">'
    . 'Издатель</A>';
$targ  = '_viewCaCert' . $issuer->Id;
$ca_cn = ($issuer->CommonName) ? $issuer->CommonName : 'not set';
$ca_hr = '<A TARGET="' . $targ . '" HREF="' . $qs_issuer . '">'
       . $ca_cn . '</A>';
// expired or revoked?
$class = 'certData';
$expired = $issuer->isExpired();
$revoked = $issuer->isRevoked();
if ($expired) { $class .= ' expired'; }
if ($revoked) { $class .= ' revoked'; }
// expiring soon?
if (!$expired and !$revoked) {
	if ($issuer->isExpired(30)) {
		$issuer .= ' expire30';
		} elseif ($issuer->isExpired(60)) {
		$issuer .= ' expire60';
		} elseif ($issuer->isExpired(90)) {
		$issuer .= ' expire90';
		}
	}
?>
<DIV ID="dataCategory"><?= $hr; ?></DIV>
<DIV ID="<?= $id; ?>" STYLE="display: none">
<TABLE ALIGN="center">
	<TR>
		<TH>
			commonName
		</TH>
		<TD CLASS="<?= $class; ?>">
			<?= $ca_hr; ?>
		</TD>
	</TR>
<? if ($issuer->OrgName) { ?>
	<TR>
		<TH>
			Организация
		</TH>
		<TD CLASS="<?= $class; ?>">
			<?= $issuer->OrgName; ?>
		</TD>
	</TR>
<? } ?>
<? if ($issuer->OrgUnitName) { ?>
	<TR>
		<TH>
			Отдел
		</TH>
		<TD CLASS="<?= $class; ?>">
			<?= $issuer->OrgUnitName; ?>
		</TD>
	</TR>
<? } ?>
<? if ($revoked) { ?>
	<TR>
		<TH>
			Дата отзыва
		</TH>
		<TD CLASS="<?= $class; ?>">
			<?= $issuer->RevokeDate; ?>
		</TD>
	</TR>
<? } else {
if ($issuer->ValidFrom and $issuer->ValidTo) { ?>
	<TR>
		<TH>
			Срок действия
		</TH>
		<TD>
			<?= $issuer->ValidFrom; ?> до <?= $issuer->ValidTo; ?>
		</TD>
	</TR>
<? }
}
?>
</TABLE>
</DIV>
<? } ?>

<? if (is_array($signedCaCerts) and count($signedCaCerts) > 0) {
$id = 'tog_' . $this->getNumber();
$hr = '<A HREF="javascript:void(0)" ONCLICK="toggleDisplay(\'' . $id . '\')">'
    . 'Промежуточные сертификаты подписанные этим УЦ</A>';
?>
<DIV ID="dataCategory"><?= $hr; ?></DIV>
<DIV ID="<?= $id; ?>" STYLE="display: none">
<TABLE ALIGN="center">
	<TR>
		<TH>commonName</TH>
		<TH>validTo</TH>
	</TR>
<? foreach($signedCaCerts as &$c) {
	$class = 'certData';
	$qs    = $qs_ca_cert . $c->Id;
	$targ  = '_viewCert' . $c->Id;
	$txt   = (strlen($c->CommonName) > 0) ? $c->CommonName : 'not set';
	$hr    = '<A TARGET="' . $targ . '" HREF="' . $qs . '">'
	       . $txt . '</A>';
	$expired = $c->isExpired();
	$revoked = $c->isRevoked();
	if ($expired) { $class .= ' expired'; }
	if ($revoked) { $class .= ' revoked'; }
	// expiring soon?
	if (!$revoked and !$expired) {
		if ($c->isExpired(30)) {
			$class .= ' expire30';
			} elseif ($c->isExpired(60)) {
			$class .= ' expire60';
			} elseif ($c->isExpired(90)) {
			$class .= ' expire90';
			}
		}
	$validTo = ($revoked) ? $c->RevokeDate : $c->ValidTo;
	?>
	<TR>
		<TD CLASS="<?= $class; ?>"><?= $hr; ?></TD>
		<TD CLASS="<?= $class; ?>"><?= $validTo; ?></TD>
	</TR>
<? } ?>
</TABLE>
</DIV>
<? } ?>

<? if (is_array($signedClientCerts) and count($signedClientCerts) > 0) {
$id = 'tog_' . $this->getNumber();
$hr = '<A HREF="javascript:void(0)" ONCLICK="toggleDisplay(\'' . $id . '\')">'
    . 'Клиентские сертификаты, подписанные данным УЦ</A>';
?>
<DIV ID="dataCategory"><?= $hr; ?></DIV>
<DIV ID="<?= $id; ?>" STYLE="display: none">
<TABLE ALIGN="center">
	<TR>
		<TH>commonName</TH>
		<TH>validTo</TH>
	</TR>
<? foreach($signedClientCerts as &$c) {
	$class = 'certData';
	$qs    = $qs_client_cert . $c->Id;
	$targ  = '_viewCert' . $c->Id;
	$hr    = '<A TARGET="' . $targ . '" HREF="' . $qs . '">'
	       . $c->CommonName . '</A>';
	$expired = $c->isExpired();
	$revoked = $c->isRevoked();
	if ($expired) { $class .= ' expired'; }
	if ($revoked) { $class .= ' revoked'; }
	// expiring soon?
	if (!$revoked and !$expired) {
		if ($c->isExpired(30)) {
			$class .= ' expire30';
			} elseif ($c->isExpired(60)) {
			$class .= ' expire60';
			} elseif ($c->isExpired(90)) {
			$class .= ' expire90';
			}
		}
	$validTo = ($revoked) ? $c->RevokeDate : $c->ValidTo;
?>
	<TR>
		<TD CLASS="<?= $class; ?>"><?= $hr; ?></TD>
		<TD CLASS="<?= $class; ?>"><?= $validTo; ?></TD>
	</TR>
<? } ?>
</TABLE>
</DIV>
<? } ?>

<? if (is_array($signedServerCerts) and count($signedServerCerts) > 0) {
$id = 'tog_' . $this->getNumber();
$hr = '<A HREF="javascript:void(0)" ONCLICK="toggleDisplay(\'' . $id . '\')">'
    . 'Серверные сертификаты, подписанные данным УЦ</A>';
?>
<DIV ID="dataCategory"><?= $hr; ?></DIV>
<DIV ID="<?= $id; ?>" STYLE="display: none">
<TABLE ALIGN="center">
	<TR>
		<TH>commonName</TH>
		<TH>validTo</TH>
	</TR>
<? foreach($signedServerCerts as &$c) {
	$class = 'certData';
	$qs    = $qs_server_cert . $c->Id;
	$targ  = '_viewCert' . $c->Id;
	$hr    = '<A TARGET="' . $targ . '" HREF="' . $qs . '">'
	       . $c->CommonName . '</A>';
	$expired = $c->isExpired();
	$revoked = $c->isRevoked();
	if ($expired) { $class .= ' expired'; }
	if ($revoked) { $class .= ' revoked'; }
	// expiring soon?
	if (!$revoked and !$expired) {
		if ($c->isExpired(30)) {
			$class .= ' expire30';
			} elseif ($c->isExpired(60)) {
			$class .= ' expire60';
			} elseif ($c->isExpired(90)) {
			$class .= ' expire90';
			}
		}
	$validTo = ($revoked) ? $c->RevokeDate : $c->ValidTo;
?>
	<TR>
		<TD CLASS="<?= $class; ?>"><?= $hr; ?></TD>
		<TD CLASS="<?= $class; ?>"><?= $validTo; ?></TD>
	</TR>
<? } ?>
</TABLE>
</DIV>
<? } ?>

<?
$id  = 'tog_' . $this->getNumber();
$hr = '<A HREF="javascript:void(0)" ONCLICK="toggleDisplay(\'' . $id . '\')">'
    . 'Сертификат</A>';
?>
<DIV ID="dataCategory"><?= $hr; ?></DIV>
<DIV ID="<?= $id; ?>" STYLE="display: none">
<TABLE ALIGN="center">
	<TR>
		<TD>
			<PRE><?= $cert->Certificate . "\n"; ?></PRE>
		</TD>
	</TR>
</TABLE>
</DIV>
<? if ($cert->hasPrivateKey()) { ?>
<?
$id  = 'tog_' . $this->getNumber();
$hr = '<A HREF="javascript:void(0)" ONCLICK="toggleDisplay(\'' . $id . '\')">'
    . 'Приватный ключ</A>';
?>
<DIV ID="dataCategory"><?= $hr; ?></DIV>
<DIV ID="<?= $id; ?>" STYLE="display: none">
<TABLE ALIGN="center">
	<TR>
		<TD>
			<PRE><?= $cert->PrivateKey . "\n"; ?></PRE>
		</TD>
	</TR>
</TABLE>
</DIV>
<? } ?>
<?
$id  = 'tog_' . $this->getNumber();
$hr = '<A HREF="javascript:void(0)" ONCLICK="toggleDisplay(\'' . $id . '\')">'
    . 'Публичный ключ</A>';
?>
<DIV ID="dataCategory"><?= $hr; ?></DIV>
<DIV ID="<?= $id; ?>" STYLE="display: none">
<TABLE ALIGN="center">
	<TR>
		<TD>
			<PRE><?= $cert->PublicKey . "\n"; ?></PRE>
		</TD>
	</TR>
</TABLE>
</DIV>
<? if ($cert->CSR) { ?>
<?
$id  = 'tog_' . $this->getNumber();
$hr = '<A HREF="javascript:void(0)" ONCLICK="toggleDisplay(\'' . $id . '\')">'
    . 'Запрос на сертификат</A>';
?>
<DIV ID="dataCategory"><?= $hr; ?></DIV>
<DIV ID="<?= $id; ?>" STYLE="display: none">
<TABLE ALIGN="center">
	<TR>
		<TD>
			<PRE><?= $cert->CSR . "\n"; ?></PRE>
		</TD>
	</TR>
</TABLE>
</DIV>
<? } ?>
<?= $this->getPageFooter(); ?>
