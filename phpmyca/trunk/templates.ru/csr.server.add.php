<?
/**
 * @package    phpmyca
 * @author     Mike Green <mdgreen@gmail.com>
 * @copyright  Copyright (c) 2010, Mike Green
 * @license    http://opensource.org/licenses/gpl-2.0.php GPLv2
 */
(basename($_SERVER['PHP_SELF']) == basename(__FILE__)) && die('Access Denied');
// breadcrumb
$qs_back = $this->getMenuQs(MENU_CERT_REQUESTS);

// footer links
$this->addMenuLink($qs_back,'Отмена','redoutline');
$this->addMenuLink('javascript:clearForm(document.addcert);','Очистить поля','greenoutline');
$this->addMenuLink('javascript:document.addcert.submit();','Создать серверный запрос (CSR)','greenoutline');
?>
<?= $this->getPageHeader(false,true); ?>
<?= $this->getFormHeader('addcert'); ?>
<?= $this->getFormBreadCrumb(); ?>
<INPUT TYPE="hidden" NAME="<?= WA_QS_CONFIRM; ?>" VALUE="yes">
<? if (isset($_POST['serverId'])) { ?>
<INPUT TYPE="hidden" NAME="serverId" value="<?= $_POST['serverId']; ?>">
<? } ?>
<P>
Внесите необходимые данные для создания запроса (CSR) к серверу на сертификат.
Будьте внимательны, исправить приведенную информацию будет невозможно. 
В этом случае придется создавать новый запрос с корректными данными.
</P>
<TABLE>
	<COLGROUP><COL WIDTH="180px"></COLGROUP>
<? $val = (isset($_POST['CommonName'])) ? $_POST['CommonName'] : ''; ?>
	<TR>
		<TH>Host Name</TH>
		<TD>
			<INPUT TYPE="text" NAME="CommonName" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
	    <TD>
			(commonName) - полное имя сервера включая имя хоста и домен.
	    </TD>
	</TR>
<? $val = (isset($_POST['OrgName'])) ? $_POST['OrgName'] : ''; ?>
	<TR>
		<TH>Название организации</TH>
		<TD>
			<INPUT TYPE="text" NAME="OrgName" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
		<TD>
			(Название организации)
		</TD>
	</TR>
<? $val = (isset($_POST['OrgUnitName'])) ? $_POST['OrgUnitName'] : ''; ?>
	<TR>
		<TH>Название отдела</TH>
		<TD>
			<INPUT TYPE="text" NAME="OrgUnitName" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
		<TD>
			(Название отдела или департамента организации)
		</TD>
	</TR>
<? $val = (isset($_POST['EmailAddress'])) ? $_POST['EmailAddress'] : ''; ?>
	<TR>
		<TH>Email для контакта</TH>
		<TD>
			<INPUT TYPE="text" NAME="EmailAddress" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
		<TD>
			(адрес электронной почты для контакта)
		</TD>
	</TR>
<? $val = (isset($_POST['LocalityName'])) ? $_POST['LocalityName'] : ''; ?>
	<TR>
		<TH>Населенный пункт</TH>
		<TD>
			<INPUT TYPE="text" NAME="LocalityName" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
		<TD>
			(укажите название населенного пункта)
		</TD>
	</TR>
<? $val = (isset($_POST['StateName'])) ? $_POST['StateName'] : ''; ?>
	<TR>
		<TH>Область/Край</TH>
		<TD>
			<INPUT TYPE="text" NAME="StateName" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
		<TD>
			(укажите область или край)
		</TD>
	</TR>
<? $val = (isset($_POST['CountryName'])) ? $_POST['CountryName'] : ''; ?>
	<TR>
		<TH>Страна</TH>
		<TD>
			<INPUT TYPE="text" NAME="CountryName" VALUE="<?= $val; ?>" SIZE="2">
		</TD>
		<TD>
			(Укажите двухбуквенное обозначение страны (RU для России))
		</TD>
	</TR>
<? $val = (isset($_POST['Пароль'])) ? $_POST['PassPhrase'] : ''; ?>
	<TR>
		<TH>Пароль</TH>
		<TD>
			<INPUT TYPE="password" NAME="PassPhrase" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
		<TD>
			Пароль для приватного ключа, опционально.
		</TD>
	</TR>
<? $val = (isset($_POST['ExportPassPhrase'])) ? $_POST['ExportPassPhrase'] : ''; ?>
	<TR>
		<TH>Пароль для экспорта</TH>
		<TD>
			<INPUT TYPE="password" NAME="ExportPassPhrase" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
		<TD>
			Пароль для экспорта.
		</TD>
	</TR>
</TABLE>
<?= $this->getFormFooter(); ?>
<?= $this->getPageFooter(); ?>
