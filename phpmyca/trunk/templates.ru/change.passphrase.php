<?
/**
 * phpmyca - change private key pass phrase
 * @package    phpmyca
 * @author     Mike Green <mdgreen@gmail.com>
 * @copyright  Copyright (c) 2010, Mike Green
 * @license    http://opensource.org/licenses/gpl-2.0.php GPLv2
 */
(basename($_SERVER['PHP_SELF']) == basename(__FILE__)) && die('Access Denied');

$data =& $this->getVar('data');
if (!is_a($data,'phpmycadbo')) {
	$m = 'Required data is missing, cannot continue.';
	die($this->getPageError($m));
	}

$qs_back    = $this->getActionQs($data->actionQsView);

// footer links
$this->addMenuLink($qs_back,'Назад','greenoutline');
$this->addMenuLink('javascript:clearForm(document.getpass);','Очистить поля','greenoutline');
$this->addMenuLink('javascript:document.getpass.submit();','Продолжить','greenoutline');
?>
<?= $this->getPageHeader(); ?>
<?= $this->getFormHeader('getpass'); ?>
<?= $this->getFormBreadCrumb(); ?>
<P>
Enter the old and new private key pass phrases in the form fields below.
</P>
<TABLE ALIGN="center">
<? $val = (isset($_POST['keyPass'])) ? $_POST['keyPass'] : ''; ?>
	<TR>
		<TH>Текущий пароль приватного ключа</TH>
		<TD>
			<INPUT TYPE="password" NAME="keyPass" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
	</TR>
<? $val = (isset($_POST['newPass'])) ? $_POST['newPass'] : ''; ?>
	<TR>
		<TH>Новый пароль приватного ключа</TH>
		<TD>
			<INPUT TYPE="password" NAME="newPass" VALUE="<?= $val; ?>" SIZE="40" MAXLENGTH="64">
		</TD>
	</TR>
</TABLE>
<?= $this->getFormFooter(); ?>
<?= $this->getPageFooter(); ?>
