<?
/**
 * @package    phpmyca
 * @author     Mike Green <mdgreen@gmail.com>
 * @copyright  Copyright (c) 2010, Mike Green
 * @license    http://opensource.org/licenses/gpl-2.0.php GPLv2
 */
(basename($_SERVER['PHP_SELF']) == basename(__FILE__)) && die('Access Denied');
// breadcrumb
$qs_back = $this->getMenuQs(MENU_CERTS_CA);

// message passing url when a CA is selected
$popUrl = $this->getPopulateFormDataQs(WA_ACTION_CA_POPULATE_FORM,0);

// Add in form utility javascript
$this->htmlJsAdd('js/formUtil.js');

// footer links
$this->addMenuLink($qs_back,'Отмена','redoutline');
$this->addMenuLink('javascript:clearForm(document.addcert);','Очистить форму','greenoutline');
$this->addMenuLink('javascript:document.addcert.submit();','Создать УЦ','greenoutline');
?>
<?= $this->getPageHeader(false,true); ?>
<?= $this->getFormHeader('addcert'); ?>
<?= $this->getFormBreadCrumb(); ?>
<INPUT TYPE="hidden" NAME="<? echo WA_QS_CONFIRM; ?>" VALUE="yes">
<P>
Новый УЦ может быть как самоподписываемым (корневым) или дочерним для выбранного УЦ.
Для создания дочернего сертификата выберите из списка существующий УЦ (Издателя) и укажите пароль, если он установлен.
</P>
<TABLE>
	<COLGROUP><COL WIDTH="180px"></COLGROUP>
<? $val = (isset($_POST['caId'])) ? $_POST['caId'] : 'self'; ?>
	<TR>
		<TH>Издатель</TH>
		<TD>
			<?= $this->getFormSelectCa('caId',$val,'caSelected(this,\'' . $popUrl . '\');'); ?>
		</TD>
		<TD>
			Выбирайте, если необходимо создать дочерний сертификат
		</TD>
	</TR>
<? $val = (isset($_POST['caPassPhrase'])) ? $_POST['caPassPhrase'] : ''; ?>
	<TR>
		<TH>Пароль издателя</TH>
		<TD COLSPAN="2">
			<INPUT TYPE="password" NAME="caPassPhrase" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
	</TR>
</TABLE>
<P>
Внесите  информацию для создания сертификата Удостоверяющего центра (УЦ)
Неверно указанные данные после создания сертификата не подлежат изменению. В этом случае потребуется создание нового сертификата с корректными данными. 
Прежний сертификат и сертификаты, выданные на его основе будут недействительны.
</P>
<TABLE>
	<COLGROUP><COL WIDTH="180px"></COLGROUP>
<? $val = (isset($_POST['CommonName'])) ? $_POST['CommonName'] : ''; ?>
	<TR>
		<TH>Имя УЦ</TH>
		<TD>
			<INPUT TYPE="text" NAME="CommonName" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
	    <TD>
			(Название УЦ)
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
			(Название отдела/департамента организации)
		</TD>
	</TR>
</TABLE>
<P>
Укажите реальное местоположение удостоверяющего центра. Название страны должно быть двухбуквенным согласно ISO
</P>
<TABLE>
	<COLGROUP><COL WIDTH="180px"></COLGROUP>
<? $val = (isset($_POST['LocalityName'])) ? $_POST['LocalityName'] : ''; ?>
	<TR>
		<TH>Город</TH>
		<TD>
			<INPUT TYPE="text" NAME="LocalityName" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
		<TD>
			(Название населенного пункта)
		</TD>
	</TR>
<? $val = (isset($_POST['CountryName'])) ? $_POST['CountryName'] : ''; ?>
	<TR>
		<TH>Страна</TH>
		<TD>
			<INPUT TYPE="text" NAME="CountryName" VALUE="<?= $val; ?>" SIZE="2">
		</TD>
		<TD>
			(Название страны, например: RU)
		</TD>
	</TR>
<? $val = (isset($_POST['Days'])) ? $_POST['Days'] : ''; ?>
	<TR>
		<TH>Срок в днях</TH>
		<TD>
			<INPUT TYPE="text" NAME="Days" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="5">
		</TD>
		<TD>
			Срок действия сертификата в днях.
		</TD>
	</TR>
</TABLE>
<P>
Вы можете указать пароль для шифрации приватного ключа.
</P>
<TABLE>
	<COLGROUP><COL WIDTH="180px"></COLGROUP>
<? $val = (isset($_POST['PassPhrase'])) ? $_POST['PassPhrase'] : ''; ?>
	<TR>
		<TH>Пароль</TH>
		<TD COLSPAN="2">
			<INPUT TYPE="password" NAME="PassPhrase" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
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
