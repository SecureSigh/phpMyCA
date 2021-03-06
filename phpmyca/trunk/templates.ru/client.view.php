<?
/**
 * phpmyca - view client certificate
 * @package    phpmyca
 * @author     Mike Green <mdgreen@gmail.com>
 * @copyright  Copyright (c) 2010, Mike Green
 * @license    http://opensource.org/licenses/gpl-2.0.php GPLv2
 */
(basename($_SERVER['PHP_SELF']) == basename(__FILE__)) && die('Access Denied');

$cert =& $this->getVar('cert');
if (!($cert instanceof phpmycaCert)) {
	$m = 'Отсутствует серверный сертификат.';
	die($this->getPageError($m));
	}

$issuer =& $this->getVar('issuer');
if (!($issuer instanceof phpmycaCert)) {
	$m = 'Отсутствует сертификат издателя.';
	die($this->getPageError($m));
	}

$qs_back     = $this->getActionQs(WA_ACTION_CLIENT_LIST);
$qs_pkcs12   = $this->getActionQs(WA_ACTION_CLIENT_PKCS12);
$qs_issuer   = $this->getMenuQs(MENU_CERTS_CA)
             . '&' . WA_QS_ACTION . '=' . WA_ACTION_CA_VIEW
             . '&' . WA_QS_ID . '=' . $cert->ParentId;
$qs_bundle   = $this->getActionQs(WA_ACTION_BUNDLE);
$qs_revoke   = $this->getActionQs(WA_ACTION_CLIENT_REVOKE);

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
			$this->addMenuLink($qs,'Изменить пароль приватного ключа','greenoutline');
			$qs = $this->getActionQs(WA_ACTION_DECRYPT);
			$this->addMenuLink($qs,'Расшифровать прив. ключ','greenoutline');
			} else {
			$qs = $this->getActionQs(WA_ACTION_ENCRYPT);
			$this->addMenuLink($qs,'Зашифровать прив. ключ','greenoutline');
			}
		}
	$this->addMenuLink($qs_bundle,'Скачать  PEM УЦ','greenoutline');
	$this->addMenuLink($qs_pkcs12,'Скачать PKCS12','greenoutline');
	}
$this->addMenuLink($qs_back,'Back','greenoutline');
?>
<?= $this->getPageHeader(); ?>
<TABLE ALIGN="center">
	<TR>
		<TH>Идентификатор сертификата</TH>
		<TD>
			<?= $cert->Id . "\n"; ?>
		</TD>
	</TR>
	<TR>
		<TH>Имя владельца (commonName)</TH>
		<TD>
			<?= $cert->CommonName . "\n"; ?>
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
<? if ($cert->EmailAddress) { ?>
	<TR>
		<TH>Email</TH>
		<TD><?= $cert->EmailAddress; ?></TD>
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
	<TR>
		<TH COLSPAN="2">Контрольные суммы</TH>
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
			Имя (commonName)
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
		<TD CLASS="<?= $class; ?>">
			<?= $issuer->ValidFrom; ?> до <?= $issuer->ValidTo; ?>
		</TD>
	</TR>
<? }
}
?>
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
<?
if ($cert->hasPrivateKey()) {
$id  = 'tog_' . $this->getNumber();
$hr = '<A HREF="javascript:void(0)" ONCLICK="toggleDisplay(\'' . $id . '\')">'
    . 'Приватный ключ</A>';
?>
<DIV ID="dataCategory"><?= $hr; ?></DIV>
<DIV ID="<?= $id; ?>" STYLE="display: none">
<TABLE ALIGN="center">
	<TR>
		<TD>
			<PRE><?= $cert->PrivateKey; ?></PRE>
		</TD>
	</TR>
</TABLE>
</DIV>
<? } ?>
<?
if ($cert->hasPublicKey()) {
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
<? } ?>
<? if ($cert->hasCsr()) { ?>
<?
$id  = 'tog_' . $this->getNumber();
$hr = '<A HREF="javascript:void(0)" ONCLICK="toggleDisplay(\'' . $id . '\')">'
    . 'Запрос сертификата</A>';
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
