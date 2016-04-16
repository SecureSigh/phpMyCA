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
$this->addMenuLink($qs_back,'Отмена','redoutline');
$this->addMenuLink('javascript:clearForm(document.importcert);','Очистить поля','greenoutline');
$this->addMenuLink('javascript:document.importcert.submit();','Импортировать УЦ','greenoutline');
?>
<?= $this->getPageHeader(false,true); ?>
<?= $this->getFormHeader('importcert',null,null,true); ?>
<?= $this->getFormBreadCrumb(); ?>
<INPUT TYPE="hidden" NAME="<? echo WA_QS_CONFIRM; ?>" VALUE="yes">
<INPUT TYPE="hidden" NAME="MAX_FILE_SIZE" value="20000">
<P>
Сертификат должен быть в формате PEM. 
Можно загрузить соответствующие файлы или скопировать и вставить сами сертификаты в нужные текстовые поля.
Если приватный ключ зашифрован - укажите его пароль.
</P>
<TABLE>
	<TR>
		<TH>
			Пароль приватного ключа
		</TH>
		<TD>
			<INPUT TYPE="password" NAME="pass" SIZE="30" MAXLENGTH="100" VALUE="<?= (isset($_POST['pass'])) ? $_POST['pass'] : ''; ?>">
		</TD>
	</TR>
	<TR>
		<TH COLSPAN="2">
			Загрузить файл
		</TH>
	</TR>
	<TR>
		<TD>
			Сертификат
		</TD>
		<TD>
			<INPUT TYPE="file" name="cert_file">
		</TD>
	</TR>
	<TR>
		<TD>
			Приватный ключ
		</TD>
		<TD>
			<INPUT TYPE="file" name="key_file">
		</TD>
	</TR>
	<TR>
		<TD>
			Запрос CSR
		</TD>
		<TD>
			<INPUT TYPE="file" name="csr_file">
		</TD>
	</TR>
	<TR>
		<TH COLSPAN="2">Сертификат (обязательное)</TH>
	</TR>

	<TR>
		<TD COLSPAN="2">
			<TEXTAREA NAME="cert" COLS="70" ROWS="28"><? if (isset($_POST['cert'])) { echo $_POST['cert']; } ?></TEXTAREA>
		</TD>
	</TR>
	<TR>
		<TH COLSPAN="2">Приватный ключ (опциональное)</TH>
	</TR>
	<TR>
		<TD COLSPAN="2">
			<TEXTAREA NAME="key" COLS="70" ROWS="28"><? if (isset($_POST['key'])) { echo $_POST['key']; } ?></TEXTAREA>
		</TD>
	</TR>
	<TR>
		<TH COLSPAN="2">CSR (опциональное)</TH>
	</TR>
	<TR>
		<TD COLSPAN="2">
			<TEXTAREA NAME="csr" COLS="70" ROWS="28"><? if (isset($_POST['csr'])) { echo $_POST['csr']; } ?></TEXTAREA>
		</TD>
	</TR>
</TABLE>
<?= $this->getFormFooter(); ?>
<?= $this->getPageFooter(); ?>
