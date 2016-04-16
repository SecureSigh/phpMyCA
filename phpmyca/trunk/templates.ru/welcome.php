<?
/**
 * phpmyca welcome screen
 * @package    phpmyca
 * @author     Mike Green <mdgreen@gmail.com>
 * @copyright  Copyright (c) 2010, Mike Green
 * @license    http://opensource.org/licenses/gpl-2.0.php GPLv2
 */
(basename($_SERVER['PHP_SELF']) == basename(__FILE__)) && die('Access Denied');
?>
<?= $this->getPageHeader(); ?>
<TABLE ALIGN="center">
	<TR>
		<TH>
			<A HREF="<?= $this->getMenuQs(MENU_CERTS_CA); ?>">Управление Удостоверяющим центром</A>
		</TH>
		<TD>
			Операции относятся к управлению сертификатами Удостоверяющих Центров согласно X.509.
			Список УЦ, создание нового УЦ, список клиентских сертификатов, выданных (подписанных) УЦ,
			отзыв клиентских сертификатов и т.п.
		</TD>
	</TR>
	<TR>
		<TH>
			<A HREF="<?= $this->getMenuQs(MENU_CERTS_SERVER); ?>">Управление серверными сертификатами</A>
		</TH>
		<TD>
			Операции относятся к серверным сертификатам.
			Список серверных сертификатов, создание сертификата и т.п.
		</TD>
	</TR>
	<TR>
		<TH>
			<A HREF="<?= $this->getMenuQs(MENU_CERT_REQUESTS); ?>">Управление запросами на получение сертификата</A>
		</TH>
		<TD>
			Создание и управление запросами на сертификаты.
		</TD>
	</TR>

	<TR>
		<TH>
			<A HREF="<?= $this->getMenuQs(MENU_CERTS_CLIENT); ?>">Управление клиентскими сертификатами</A>
		</TH>
		<TD>
			Операции связанные с упрвлением клиентскими сертификатами.
			список сертификатов, создание новых и т.п.
		</TD>
	</TR>
	<TR>
		<TH>
			<A HREF="<?= $this->getMenuQs(MENU_UTILITIES); ?>">Утилиты</A>
		</TH>
		<TD>
			Набор утилит для работы с сертификатами.
		</TD>
	</TR>
</TABLE>
<?= $this->getPageFooter(); ?>
