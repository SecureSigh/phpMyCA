<?
/**
 * @package    phpmyca
 * @author     Mike Green <mdgreen@gmail.com>
 * @copyright  Copyright (c) 2010, Mike Green
 * @license    http://opensource.org/licenses/gpl-2.0.php GPLv2
 */
(basename($_SERVER['PHP_SELF']) == basename(__FILE__)) && die('Access Denied');
// breadcrumb
$qs_back = $this->getMenuQs(MENU_CERTS_SERVER);

// footer links
$this->addMenuLink($qs_back,'Отмена','redoutline');
$this->addMenuLink('javascript:clearForm(document.addcert);','Очистить поля','greenoutline');
$this->addMenuLink('javascript:document.addcert.submit();','Создать серверный сертификат','greenoutline');

// message passing url when a CA is selected
$popUrl = $this->getPopulateFormDataQs(WA_ACTION_CA_POPULATE_FORM,0);

// Add in form utility javascript
$this->htmlJsAdd('js/formUtil.js');
?>
<?= $this->getPageHeader(false,true); ?>
<?= $this->getFormHeader('addcert'); ?>
<?= $this->getFormBreadCrumb(); ?>
<INPUT TYPE="hidden" NAME="<? echo WA_QS_CONFIRM; ?>" VALUE="yes">
<P>
Заполните поля для генерации сертификата. Будьте внимательны, после создания сертификата введенную информацию изменить невозможно.
В этом случае необходимо будет создавать новый сертификат с корректными данными и перевыпустить все клиентские сертификаты.
</P>
<TABLE>
	<COLGROUP><COL WIDTH="180px"></COLGROUP>
<? $val = (isset($_POST['caId'])) ? $_POST['caId'] : false; ?>
	<TR>
		<TH>Подписывающий УЦ</TH>
		<TD COLSPAN="2">
			<?= $this->getFormSelectCa('caId',$val,'caSelected(this,\'' . $popUrl . '\');'); ?>
		</TD>
	</TR>
<? $val = (isset($_POST['caPassPhrase'])) ? $_POST['caPassPhrase'] : ''; ?>
	<TR>
		<TH>Пароль подписывающего УЦ</TH>
		<TD>
			<INPUT TYPE="password" NAME="caPassPhrase" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
	    <TD>
			Введите пароль УЦ если необходимо.
	    </TD>
	</TR>
<? $val = (isset($_POST['CommonName'])) ? $_POST['CommonName'] : ''; ?>
	<TR>
		<TH>Имя сервера (hostname)</TH>
		<TD>
			<INPUT TYPE="text" NAME="CommonName" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
	    <TD>
			(commonName) - название хоста с указанием домена.
	    </TD>
	</TR>
<? $val = (isset($_POST['OrgName'])) ? $_POST['OrgName'] : ''; ?>
	<TR>
		<TH>Название организации</TH>
		<TD>
			<INPUT TYPE="text" NAME="OrgName" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
		<TD>
			(название организации)
		</TD>
	</TR>
<? $val = (isset($_POST['OrgUnitName'])) ? $_POST['OrgUnitName'] : ''; ?>
	<TR>
		<TH>Название отдела</TH>
		<TD>
			<INPUT TYPE="text" NAME="OrgUnitName" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
		<TD>
			(название отдела/департамента организации)
		</TD>
	</TR>
<? $val = (isset($_POST['EmailAddress'])) ? $_POST['EmailAddress'] : ''; ?>
	<TR>
		<TH>Email для контакта</TH>
		<TD>
			<INPUT TYPE="text" NAME="EmailAddress" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
		<TD>
			(укажите e-mail адрес)
		</TD>
	</TR>
<? $val = (isset($_POST['LocalityName'])) ? $_POST['LocalityName'] : ''; ?>
	<TR>
		<TH>Населенный пункт</TH>
		<TD>
			<INPUT TYPE="text" NAME="LocalityName" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
		<TD>
			(название населенного пункта)
		</TD>
	</TR>
<? $val = (isset($_POST['StateName'])) ? $_POST['StateName'] : ''; ?>
	<TR>
		<TH>Край/Область</TH>
		<TD>
			<INPUT TYPE="text" NAME="StateName" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
		<TD>
			(Край или область)
		</TD>
	</TR>
<? $val = (isset($_POST['CountryName'])) ? $_POST['CountryName'] : ''; ?>
	<TR>
		<TH>Страна</TH>
		<TD>
			<INPUT TYPE="text" NAME="CountryName" VALUE="<?= $val; ?>" SIZE="2">
		</TD>
		<TD>
			(двухбуквенное обозначение страны (RU для России))
		</TD>
	</TR>
<? $val = (isset($_POST['Days'])) ? $_POST['Days'] : ''; ?>
	<TR>
		<TH>Срок действия в днях</TH>
		<TD>
			<INPUT TYPE="text" NAME="Days" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="5">
		</TD>
		<TD>
			Количество дней в течение которых будет действовать сертификат
		</TD>
	</TR>
<? $val = (isset($_POST['PassPhrase'])) ? $_POST['PassPhrase'] : ''; ?>
	<TR>
		<TH>Пароль</TH>
		<TD>
			<INPUT TYPE="password" NAME="PassPhrase" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
		<TD>
			Пароль для приватного ключа, если необходимо.
		</TD>
	</TR>
</TABLE>
<?= $this->getFormFooter(); ?>
<?= $this->getMessageFrame(); ?>
<script type="text/javascript">
// auto-populate on page load
var el = document.getElementsByName('caId')[0];
if (el) {
	var curVal = el.value;
	<?= 'var url = "' . $popUrl; ?>";
	caSelected(el,url);
	}
</script>
<?= $this->getPageFooter(); ?>
