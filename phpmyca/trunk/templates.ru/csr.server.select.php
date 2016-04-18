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
$this->addMenuLink('javascript:document.addcert.submit();','Продолжить','greenoutline');
?>
<?= $this->getPageHeader(false,true); ?>
<?= $this->getFormHeader('addcert'); ?>
<?= $this->getFormBreadCrumb(); ?>
<P>
Поля для запроса могут быть взяты из выбранного серверного сертификата или заполнены вручну.
Выберите из списка серверный сертификат или  "Туц" и заполните поля вручную.
</P>
<TABLE>
	<COLGROUP><COL WIDTH="180px"></COLGROUP>
<? $val = (isset($_POST['serverId'])) ? $_POST['serverId'] : false; ?>
	<TR>
		<TH>Выберите сервер</TH>
		<TD COLSPAN="2">
			<?= $this->getFormSelectServerId('serverId',$val); ?>
		</TD>
	</TR>
</TABLE>
<?= $this->getFormFooter(); ?>
<?= $this->getPageFooter(); ?>
