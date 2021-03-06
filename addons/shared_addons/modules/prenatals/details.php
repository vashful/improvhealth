<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Prenatals extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'sl' => 'Skupine',
				'en' => 'Prenatal Care',
				'br' => 'Grupos',
				'de' => 'Gruppen',
				'nl' => 'Groepen',
				'fr' => 'Groupes',
				'zh' => '群組',
				'it' => 'Gruppi',
				'ru' => 'Группы',
				'ar' => 'المجموعات',
				'cs' => 'Skupiny',
				'es' => 'Grupos',
				'fi' => 'Ryhmät',
				'el' => 'Ομάδες',
				'he' => 'קבוצות',
				'lt' => 'Grupės',
				'da' => 'Grupper'
			),
			'description' => array(
				'sl' => 'Uporabniki so lahko razvrščeni v skupine za urejanje dovoljenj',
				'en' => 'Manage Prenatal Clients',
				'br' => 'Usuários podem ser inseridos em grupos para gerenciar suas permissões.',
				'de' => 'Benutzer können zu Gruppen zusammengefasst werden um diesen Zugriffsrechte zu geben.',
				'nl' => 'Gebruikers kunnen in groepen geplaatst worden om rechten te kunnen geven.',
				'fr' => 'Les utilisateurs peuvent appartenir à des groupes afin de gérer les permissions.',
				'zh' => '用戶可以依群組分類並管理其權限',
				'it' => 'Gli utenti possono essere inseriti in gruppi per gestirne i permessi.',
				'ru' => 'Пользователей можно объединять в группы, для управления правами доступа.',
				'ar' => 'يمكن وضع المستخدمين في مجموعات لتسهيل إدارة صلاحياتهم.',
				'cs' => 'Uživatelé mohou být rozřazeni do skupin pro lepší správu oprávnění.',
				'es' => 'Los usuarios podrán ser colocados en grupos para administrar sus permisos.',
				'fi' => 'Käyttäjät voidaan liittää ryhmiin, jotta käyttöoikeuksia voidaan hallinnoida.',
				'el' => 'Οι χρήστες μπορούν να τοποθετηθούν σε ομάδες και να διαχειριστείτε τα δικαιώματά τους.',
				'he' => 'נותן אפשרות לאסוף משתמשים לקבוצות',
				'lt' => 'Vartotojai gali būti priskirti grupei tam, kad valdyti jų teises.',
				'da' => 'Brugere kan inddeles i grupper for adgangskontrol'
			),
			'frontend' => FALSE,
			'backend'  => TRUE,
			'menu'	  => 'clients',
			
	    'shortcuts' => array(
						array(
					 	   'name' => 'prenatals.add_title',
						    'uri' => 'admin/prenatals/add_client',
						    'class' => 'add'
						),
					),
			'sections' => array(
          'mylist' => array(
						array(
					 	   'name' => 'Add New Prenatal Care for this Client',
						    'uri' => 'admin/prenatals/add',
						    'class' => 'add'
						),
						array(
					 	   'name' => 'Back to Client Information Page',
						    'uri' => 'admin/clients/view',
						    'class' => 'view'
						),
					), 
					),
		);
	}

	public function install()
	{
		return TRUE;
	}

	public function uninstall()
	{
		//it's a core module, lets keep it around
		return FALSE;
	}

	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return TRUE;
	}
}
/* End of file details.php */