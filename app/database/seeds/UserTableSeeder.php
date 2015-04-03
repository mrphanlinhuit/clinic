<?php

class UserTableSeeder extends Seeder {

	public function run()
	{

		$users = json_decode('{"cols":["first_name","last_name","email","phone","address","birthday","company","country","postal","city","gender"],"data":[["Halla","Hutchinson","turpis.Nulla@ipsumsodales.co.uk","(828) 213-6112","892-7950 Turpis. St.","2001/03/29","Libero Morbi Accumsan Company","Virgin Islands, United States","47970","Orangeville","male"],["Sigourney","Bean","fringilla@pulvinararcuet.net","(527) 198-4682","Ap #380-8440 Nibh. Street","1998/02/13","Hendrerit Neque PC","New Zealand","45390","Belmont","male"],["Pamela","Glenn","ac.turpis@mollisDuissit.ca","(980) 769-1744","Ap #516-7413 Orci. Ave","1972/09/04","Cras Limited","Egypt","288173","Canoas","male"],["Althea","Cummings","facilisis.vitae@sem.org","(582) 935-5846","507-2626 Erat. Rd.","1992/08/20","Nec Mollis Vitae Limited","Ethiopia","04362","Giugliano in Campania","male"],["Oren","Koch","Vestibulum.ante.ipsum@euarcu.edu","(121) 227-7605","Ap #528-4125 Rhoncus. Av.","2011/12/22","Id Limited","Timor-Leste","438401","Presteigne","male"],["Casey","Garner","ut@scelerisqueloremipsum.net","(807) 128-7316","P.O. Box 609, 3045 Fusce Rd.","1987/08/19","Suspendisse Aliquet Sem Company","Burundi","45-617","Erpion","female"],["Basil","Hernandez","et@natoquepenatibus.ca","(233) 960-5883","2129 Erat Ave","1975/08/15","Est Corp.","Kyrgyzstan","46235","Ville de Maniwaki","male"],["Stuart","York","elementum.dui.quis@eu.ca","(148) 298-8361","9980 Nec, Avenue","1975/07/31","Dui Corp.","Guernsey","5475","Luzzara","female"],["Yen","Ball","aliquet.magna@lorem.com","(208) 224-5043","Ap #389-5320 Turpis St.","1977/03/22","Fusce Limited","Jordan","00338","Castello dell\'Acqua","male"],["Kareem","Raymond","eu.dolor@dapibusquam.edu","(962) 770-7890","7386 Quis Street","1992/07/27","Cursus Et Corporation","Jersey","69425-492","Colomiers","male"],["Reagan","Mccarty","in.sodales.elit@acmattis.net","(673) 351-2013","Ap #746-2843 Erat St.","1992/10/09","Arcu Eu Ltd","Niger","87007","Feldkirchen in Kärnten","female"],["Felicia","Obrien","nostra.per@ante.com","(391) 882-1836","Ap #949-9820 Parturient Rd.","1997/08/09","Cursus Inc.","Ukraine","17152","Lower Hutt","female"],["MacKensie","Townsend","Cras.dolor@convallis.edu","(737) 463-3081","P.O. Box 379, 6626 Sed St.","2000/09/22","Nunc Sed LLP","Christmas Island","1005","Lens","female"],["Dakota","Conner","ut@Proinnisl.com","(907) 789-6642","241-8351 Risus Rd.","1985/08/06","Nunc Foundation","Uzbekistan","13812-533","Narbolia","female"],["Elvis","Williams","eu.dolor@VivamusrhoncusDonec.net","(347) 612-4622","P.O. Box 185, 8360 Eu St.","1975/04/08","Molestie Pharetra Nibh LLP","Palestine, State of","2040","Floriffoux","male"],["Farrah","Fernandez","enim.condimentum.eget@purusgravida.co.uk","(469) 901-2851","Ap #139-3066 Quisque Rd.","1999/02/13","Aliquam Gravida Industries","Saint Kitts and Nevis","30637-154","Glimes","male"],["Len","Bell","arcu.Vestibulum@molestieSedid.edu","(653) 499-3995","Ap #964-9007 Eu Avenue","2014/03/18","Eget Company","Finland","31913","Recco","female"],["Hilary","Garza","arcu.Nunc@tincidunt.com","(379) 119-3048","Ap #386-8802 Id Ave","1985/05/12","Vestibulum LLC","Greenland","5141","Cheyenne","female"],["Cathleen","Lee","egestas.a.dui@Suspendissesagittis.org","(489) 960-8453","485-7175 Eleifend Road","2008/05/25","Eu Lacus Quisque Company","Aruba","00564-831","Idar-Oberstei","female"],["Raymond","Delgado","Cras.eu.tellus@magnis.net","(899) 935-6069","Ap #183-3503 Aenean Street","2004/05/23","Eleifend Associates","Mauritania","A4S 2G8","Reading","male"],["Clementine","Rowland","magna.tellus.faucibus@Etiamlaoreet.com","(238) 294-6012","P.O. Box 206, 4073 Penatibus Street","1984/06/05","Diam Nunc Ullamcorper Corp.","Cyprus","21814","Sennariolo","male"],["Adara","Gallagher","sapien@conubia.ca","(297) 567-0308","492-1998 Vivamus St.","1979/05/10","Lacus Aliquam Rutrum Company","United States","31736","Villa Latina","female"],["Carissa","Parks","lectus.pede@idenimCurabitur.com","(300) 773-3426","3604 Vel, Rd.","2000/07/25","Vivamus Inc.","Suriname","96957-035","Turnhout","female"],["Demetria","Gallegos","nisi.nibh@adipiscingenimmi.ca","(932) 714-8829","Ap #364-7671 Aenean Street","1984/10/31","Enim Diam Industries","Rwanda","77630","Pescantina","male"],["Elijah","Stone","convallis.est@cursus.co.uk","(996) 485-7318","547-3887 Phasellus Street","1971/08/07","Tincidunt Dui Augue Inc.","Seychelles","RK5X 6EX","Tobermory","male"],["Galena","Mcfadden","magna.Sed.eu@posuerevulputatelacus.org","(518) 716-2825","843-6552 Nulla Ave","1971/05/31","Posuere Cubilia Curae; LLC","Tuvalu","87390-586","Bedollo","male"],["Hilary","Nichols","aliquam.adipiscing@magnaPhasellusdolor.org","(987) 773-6133","5924 Sem Ave","1990/08/13","Eu LLP","Yemen","56407","Detroit","male"],["Alec","Goodman","sapien.Cras@ut.ca","(949) 687-2531","Ap #500-3748 Et Rd.","1973/11/27","Curabitur Vel Lectus Corp.","Brazil","4696","San Diego","female"],["Ashton","Payne","convallis.erat.eget@vitaesodales.org","(127) 899-0973","Ap #397-1777 Dictum Ave","1980/06/23","Nulla Ante Iaculis Foundation","Myanmar","655714","Morena","female"],["Evan","Guerrero","Phasellus@etmagnis.com","(418) 117-5406","680-3002 Sed Avenue","2001/11/25","Integer Mollis Inc.","Curaçao","20911","Varena","male"],["Samuel","Willis","ac.facilisis@massaSuspendisseeleifend.co.uk","(270) 708-2365","880-6605 Aenean Av.","2001/09/18","Iaculis Associates","India","62512","Macerata","female"],["Ursula","Conley","dictum.Proin.eget@magnaLorem.org","(377) 465-8463","406-5027 Molestie. St.","2006/07/19","Diam Associates","Qatar","4464","Castelbaldo","male"],["Len","Neal","a.neque@ipsumsodalespurus.net","(496) 348-1796","Ap #300-3354 Elementum St.","2008/09/01","Nulla Facilisi Sed Ltd","Saint Kitts and Nevis","89307","Price","female"],["Vladimir","Parks","blandit.enim@ipsumdolor.net","(157) 325-3463","Ap #241-8976 A, Rd.","2006/11/01","Mauris A Nunc PC","Iraq","647769","Santa Cruz de Tenerife","female"],["Inez","Warner","Phasellus.ornare@Aliquamgravidamauris.com","(956) 646-3256","P.O. Box 479, 8514 Malesuada Ave","1987/02/24","Euismod Et Corp.","Western Sahara","5392","Damme","female"],["Justin","Baird","sed.dictum@malesuadavel.edu","(787) 723-5962","P.O. Box 750, 6756 Luctus Street","1981/07/27","Malesuada Fringilla Incorporated","Liechtenstein","776056","Senneville","male"],["Brock","Haley","Morbi.metus@aliquam.net","(568) 282-2458","242-9964 Ligula Rd.","1992/01/23","Id Mollis LLP","Portugal","44986-598","Gulfport","male"],["Zia","Espinoza","sapien.molestie.orci@scelerisquemollisPhasellus.com","(668) 397-6599","2864 Vel Avenue","2002/06/15","Quis Pede Praesent Institute","Venezuela","896445","Strombeek-Bever","male"],["Jacqueline","Whitaker","laoreet.posuere.enim@ligulaAenean.ca","(744) 637-8541","4075 Mauris. Street","1997/12/21","Nullam Vitae Diam Industries","Chile","9340","Dallas","male"],["Geoffrey","Brewer","Sed.nec.metus@non.com","(883) 409-3984","Ap #457-7533 In Rd.","1993/01/25","Nisi Nibh Lacinia Company","Gambia","31793","Torgny","male"],["Amela","Horton","eu@dignissim.com","(820) 287-2552","7377 Iaculis Ave","1971/11/21","Interdum Libero Dui Corporation","Saint Kitts and Nevis","9331NU","Castelluccio Valmaggiore","male"],["Chaim","Mckinney","sit.amet.massa@Suspendisse.org","(999) 993-2579","6168 Id, Avenue","1981/02/22","In Faucibus Orci Corporation","Cape Verde","09532","Anzegem","female"],["Gisela","Bowen","in.sodales.elit@eleifendegestasSed.edu","(387) 399-1806","8679 Nunc Rd.","1973/10/28","Urna Convallis Erat Company","China","79565-321","Rostock","female"],["Noble","Frank","Integer@neque.com","(195) 818-6521","364-3699 Sit St.","1979/02/17","Sit Amet Consectetuer LLP","Denmark","2283","Dieppe","male"],["Martina","Parrish","nec@nec.ca","(837) 580-5261","P.O. Box 663, 5819 Arcu. Rd.","1979/09/10","Parturient Montes Nascetur Incorporated","Montserrat","29150-966","Geer","male"],["Alexis","Chaney","Sed@dapibusgravida.com","(292) 337-7593","P.O. Box 401, 6899 Eget Ave","1984/10/07","Proin Sed Turpis LLP","Uruguay","2443","Kota","female"],["Nicole","Booth","augue.eu.tempor@Utsagittis.com","(667) 413-2113","852-4316 Velit Av.","1978/08/24","Vehicula Et Rutrum Institute","Zimbabwe","PZ6W 2RQ","Holywell","female"],["Abigail","Martin","Suspendisse.aliquet@Aliquam.net","(595) 745-2145","2902 Eu, Road","2012/05/19","Etiam Bibendum Fermentum Limited","Latvia","X13 7VF","Vöcklabruck","male"],["Eagan","Figueroa","Mauris.vestibulum@Maurisvestibulum.edu","(369) 524-2721","P.O. Box 107, 2839 Ullamcorper Street","1996/08/31","Ridiculus Mus Proin Incorporated","Saint Martin","62294","Port Harcourt","male"],["Nigel","Mcpherson","tempor.est.ac@Integer.net","(474) 874-4999","P.O. Box 640, 4481 Laoreet Ave","1976/08/07","Eget Associates","Suriname","1131","Bellegem","male"],["Pandora","Hinton","per.inceptos@Namporttitor.net","(708) 294-7483","P.O. Box 640, 3262 Leo. Rd.","1993/12/16","Ut PC","Norfolk Island","81547","Wyoming","female"],["Grant","Strickland","mollis@utpharetra.net","(121) 386-8202","409-1249 Erat Street","2003/03/31","Eu Euismod Ac Consulting","Cameroon","2386","Zaanstad","male"],["Griffith","Serrano","odio.semper@etmalesuadafames.ca","(585) 321-8907","609-3092 Lectus Rd.","1980/01/28","Nec Incorporated","French Guiana","9124","Ludwigshafen","female"],["Hayden","Eaton","ligula.tortor@loremvehiculaet.co.uk","(128) 677-1006","Ap #615-157 Mauris St.","1980/09/27","Ad Litora Associates","Macedonia","06986","Beerse","female"],["Amir","Wells","auctor.velit.Aliquam@Nam.co.uk","(668) 551-2070","Ap #273-1552 Ac St.","1972/02/11","Ante Nunc Corp.","Iceland","4683","Charny","female"],["Cora","Goodman","eu.metus.In@vitaedolorDonec.edu","(295) 840-9454","6033 Posuere Rd.","2002/02/16","In Ltd","Åland Islands","9157","Vollezele","female"],["Raven","Terrell","mus.Proin.vel@Morbi.com","(508) 711-0515","793 Sed St.","1980/05/07","Lobortis Risus In Corporation","Guernsey","75087","Lelystad","female"],["Nayda","Washington","ad.litora@etrutrumeu.org","(890) 376-7362","Ap #515-8408 Ipsum. Avenue","2013/07/24","Mauris Ipsum Associates","Burundi","3735","Silverton","male"],["Nicholas","Blankenship","a@aptenttaciti.co.uk","(920) 978-1941","P.O. Box 243, 4321 Ridiculus Av.","2008/04/06","Rhoncus Donec Est Ltd","Nepal","291854","Abeokuta","male"],["Imelda","Workman","id@velsapien.net","(350) 128-3297","323-1238 Lacus. Rd.","1971/10/17","Lorem Vitae Odio Corporation","New Caledonia","IH7 0KS","New Westminster","male"],["Geoffrey","Bryan","arcu.Morbi.sit@inlobortistellus.ca","(844) 758-5752","P.O. Box 202, 1492 Lacus. Av.","1973/01/27","Arcu Foundation","Mexico","NC8 8BS","Rothesay","male"],["Julie","Morrow","montes.nascetur.ridiculus@lectuspedeultrices.org","(953) 469-4749","690-4484 Mauris Road","1975/04/05","Mauris Incorporated","Pitcairn Islands","383263","Prenzlau","female"],["Alma","Osborne","vulputate@estcongue.ca","(800) 370-2604","P.O. Box 861, 2021 Cras St.","2002/06/30","Turpis Egestas Fusce Institute","Bouvet Island","92-161","Rocourt","male"],["Caesar","Fisher","gravida@ornarefacilisiseget.org","(613) 785-2583","Ap #333-9362 Sapien, Ave","2011/12/18","Ornare Industries","Micronesia","256472","Eksaarde","male"],["Illiana","Hunt","sit@arcuetpede.net","(362) 568-0199","320-9035 Nunc St.","1999/01/26","Quisque Ornare Tortor Corporation","Guyana","79292","Rignano Garganico","male"],["Cameron","Ramirez","magna.a.neque@necante.co.uk","(859) 829-5216","343-4419 Eros. Avenue","1991/10/07","Magna Malesuada Vel Industries","Palestine, State of","6370","Habergy","female"],["Scarlett","Herman","leo@Nunc.edu","(805) 308-2150","2383 Lorem, Avenue","1979/02/13","Aliquam Adipiscing Industries","Uruguay","41312","Ila","female"],["Cassandra","Mcgee","ligula@quispede.ca","(573) 561-3843","3875 Curabitur Rd.","2000/04/25","Malesuada Vel Convallis Associates","Turkmenistan","83798","Mumbai","female"],["Emery","Gregory","Sed.dictum.Proin@miloremvehicula.net","(152) 492-7363","Ap #752-4840 Tincidunt Rd.","1999/12/12","Tortor Dictum Eu Industries","Chile","2174","Glasgow","male"],["Claudia","Rodriguez","ligula.tortor.dictum@tinciduntcongueturpis.com","(951) 556-8597","Ap #535-5615 Ac Rd.","1987/07/20","Porttitor Interdum Sed Ltd","Cape Verde","8601","Welshpool","female"],["Hadassah","Greene","Suspendisse.ac@sitamet.org","(319) 819-1192","P.O. Box 858, 710 Amet, Ave","2006/08/22","Mi Enim Associates","Japan","63798-966","Sloten","female"],["Savannah","Cohen","ipsum.ac@amagna.com","(973) 416-7388","676-9905 Magna. Ave","2012/05/21","Eget Nisi Associates","Angola","1930","Chelmsford","male"],["Ella","Holman","odio.a@lacus.co.uk","(871) 736-7389","P.O. Box 392, 980 Donec Avenue","1976/12/21","Nisl Elementum Consulting","China","DZ63 2MR","Wichita","male"],["Shoshana","Stewart","adipiscing.fringilla@Sed.org","(608) 247-3652","Ap #872-6542 Penatibus Avenue","1982/07/21","Ac Mattis LLP","United Kingdom (Great Britain)","B9L 8P2","San Clemente","male"],["Dorothy","Tyson","Curabitur.consequat@sodales.net","(635) 593-7194","6842 Congue. St.","1996/07/18","Amet Ante Corp.","British Indian Ocean Territory","18643","Itzehoe","female"],["Darius","Fuentes","suscipit.nonummy.Fusce@MaurisnullaInteger.com","(740) 253-8889","P.O. Box 436, 5603 Dolor Rd.","1997/04/16","Quisque Varius Foundation","Vanuatu","10202","Outremont","female"],["Linus","Leblanc","est.ac@euligula.edu","(849) 643-7318","9575 Morbi Rd.","1995/10/18","Hymenaeos Mauris Associates","Saint Barthélemy","45673","Dumbarton","female"],["Hamilton","Francis","consectetuer.adipiscing.elit@non.edu","(491) 353-0355","P.O. Box 727, 8224 Magnis Street","1976/08/21","A Aliquet Foundation","Suriname","620680","Stonewall","female"],["Timon","Daniels","natoque.penatibus@ipsum.org","(213) 636-8944","183-7637 Semper Av.","1986/02/19","Semper Tellus Id PC","Togo","L0 0UU","Wimmertingen","female"],["Shellie","Vazquez","eget.metus@utquam.ca","(972) 770-9289","P.O. Box 956, 274 Suscipit Avenue","2002/01/22","Ante Dictum Ltd","Belgium","6854","Orilla","female"],["Callum","Hall","pede.et@dis.net","(424) 797-3043","Ap #971-5482 Scelerisque Ave","1972/07/19","Suspendisse Dui Fusce Limited","Zambia","2215","Judenburg","male"],["Joelle","Curtis","Phasellus.at.augue@ullamcorpernisl.net","(847) 861-1987","P.O. Box 895, 9798 Vel Street","2005/12/07","Et Company","Congo (Brazzaville)","93720","Seilles","male"],["Lani","Tanner","consectetuer.adipiscing@ligula.org","(490) 314-1237","Ap #390-9257 Dis Road","1996/08/29","Rutrum Magna Incorporated","Taiwan","75053","Southend","male"],["Dai","Steele","risus@rutrum.co.uk","(200) 591-5326","P.O. Box 909, 439 Et Rd.","1998/04/22","Sit Amet Risus PC","Nepal","7276","Morolo","female"],["Orson","Sargent","ultricies.ligula.Nullam@massalobortisultrices.co.uk","(781) 208-5295","Ap #599-319 Vehicula. Road","2000/04/27","Libero Company","Saint Kitts and Nevis","89216","Raigarh","female"],["Sarah","Brooks","habitant.morbi@posuereatvelit.net","(907) 806-5461","7928 Mattis St.","1999/06/30","Gravida Praesent Limited","Tanzania","3346","Terlago","male"],["Hadley","Garcia","eros.nec@montesnascetur.com","(308) 259-2189","P.O. Box 487, 8653 Vel St.","2012/05/16","Ultrices Posuere Cubilia LLC","South Africa","6141","Baiso","male"],["Alexander","Combs","Suspendisse.sed.dolor@lectusrutrum.com","(165) 130-4558","111-3663 Egestas. Avenue","1984/12/05","Nunc Mauris Morbi Corporation","Saint Pierre and Miquelon","76847","Embourg","female"],["Hilel","Schwartz","vel.vulputate.eu@Nullainterdum.net","(989) 188-2087","594-185 Eget Rd.","2002/03/26","Turpis Ltd","Denmark","70325-901","Mussy-la-Ville","male"],["Kane","Christensen","egestas.Aliquam@orcilobortis.net","(127) 188-4716","803-5970 Nec Rd.","1995/02/23","Dolor Fusce Limited","Svalbard and Jan Mayen Islands","77451","Sant\'Angelo Limosano","female"],["Alisa","Sparks","id@Proinegetodio.net","(771) 841-7579","5919 Urna. Av.","2000/05/24","Vitae Odio Sagittis Ltd","Tokelau","626410","Pietraroja","female"],["Brock","Buckley","mus.Aenean@quis.ca","(548) 482-9488","P.O. Box 460, 2576 Non Street","1986/02/09","Sapien PC","Montenegro","63299","Saint John","male"],["Fletcher","Austin","sem.ut.dolor@euneque.net","(906) 468-1534","Ap #901-1012 Vitae Av.","1992/05/21","Metus In Lorem Corporation","Belgium","7840","Paignton","female"],["Remedios","Molina","in.tempus.eu@nunc.com","(215) 482-0212","9070 Consequat Ave","2011/09/02","Risus Donec Egestas LLC","Benin","8865","Villa Santo Stefano","female"],["Brett","Romero","vulputate.mauris@lacusvestibulum.co.uk","(871) 206-0842","6246 Ligula. Road","1972/10/30","Laoreet Libero PC","El Salvador","57982-301","D�gelis","female"],["Adria","Middleton","Aliquam@massaMauris.ca","(211) 685-8195","Ap #497-4939 Amet St.","1997/04/21","Ornare In Consulting","Fiji","4631","Amlwch","female"],["Nina","Burnett","ut.nulla@quisturpis.ca","(449) 554-2397","136-4553 Fermentum Rd.","1988/03/10","Turpis Company","Cambodia","1593","Minna","male"],["Oliver","Cox","eget.varius@magna.edu","(823) 354-3936","P.O. Box 978, 6196 Eu St.","1990/01/14","Posuere Cubilia Ltd","Taiwan","34679","Queenstown","female"],["Daquan","Olsen","vehicula@risusNunc.org","(652) 428-2289","3872 Metus. Rd.","1985/10/20","Nunc Sed Libero LLC","Bermuda","6551","Lauder","male"],["Cameron","Bishop","cubilia.Curae@nisimagna.com","(860) 723-6902","1819 Amet Street","2000/03/18","Pellentesque Tellus LLC","Singapore","39-473","Te Awamutu","male"]]}', true);
		DB::table('users')->delete();

		$admin_group   = Group::where('name', 'admin')->first();
		$manager_group = Group::where('name', 'manager')->first();
		$user_group    = Group::where('name', 'user')->first();

		$admin         = Role::where('name', 'admin')->first();
		$doctor        = Role::where('name', 'doctor')->first();
		$therapist     = Role::where('name', 'therapist')->first();
		$accounting    = Role::where('name', 'accounting')->first();
		$cashier       = Role::where('name', 'cashier')->first();
		$user          = Role::where('name', 'user')->first();

		// Admin
		$a = User::create(array(
			'group_id' => $admin_group->id,
			'status'   => 'active'
		));
		$a->addRole(array($admin->id, $accounting->id, $cashier->id));
		UserCredential::create(array(
			'user_id'  => $a->id,
			'email'    => 'admin@example.com',
			'password' => Hash::make('password'),
		));
		UserMeta::create(array(
			'user_id'    => $a->id,
			'created_by' => $a->id,
			'updated_by' => $a->id,
			'first_name' => 'John',
			'last_name'  => 'Admin'
		));

		Clinic::create(array(
			'user_id'    => $a->id,
			'openhour1'  => '08:00',
			'openhour2'  => '14:00',
			'closehour1' => '16:00',
			'closehour2' => '20:00',
			'vat_id'     => 'B123124192'
		));

		// Doctor
		$a = User::create(array(
			'group_id' => $manager_group->id,
			'status'   => 'active'
		));

		$a->addRole(array($doctor->id));
		UserCredential::create(array(
			'user_id'  => $a->id,
			'email'    => 'doctor@example.com',
			'password' => Hash::make('password'),
		));
		UserMeta::create(array(
			'user_id'    => $a->id,
			'created_by' => $a->id,
			'updated_by' => $a->id,
			'first_name' => 'John',
			'last_name'  => 'Doctor'
		));

		// Therapist
		$a = User::create(array(
			'group_id' => $user_group->id,
			'status'   => 'active'
		));

		$a->addRole(array($therapist->id));
		UserCredential::create(array(
			'user_id'  => $a->id,
			'email'    => 'therapist@example.com',
			'password' => Hash::make('password'),
		));
		UserMeta::create(array(
			'user_id'    => $a->id,
			'created_by' => $a->id,
			'updated_by' => $a->id,
			'first_name' => 'John',
			'last_name'  => 'Therapist'
		));

		// Accounting
		$a = User::create(array(
			'group_id' => $user_group->id,
			'status'   => 'active'
		));
		$a->addRole(array($accounting->id, $cashier->id));
		UserCredential::create(array(
			'user_id'  => $a->id,
			'email'    => 'accounting@example.com',
			'password' => Hash::make('password'),
		));
		UserMeta::create(array(
			'user_id'    => $a->id,
			'created_by' => $a->id,
			'updated_by' => $a->id,
			'first_name' => 'John',
			'last_name'  => 'Accounting'
		));

		// Cashier
		$a = User::create(array(
			'group_id' => $user_group->id,
			'status'   => 'active'
		));
		$a->addRole(array($cashier->id));
		UserCredential::create(array(
			'user_id'  => $a->id,
			'email'    => 'cashier@example.com',
			'password' => Hash::make('password'),
		));
		UserMeta::create(array(
			'created_by' => $a->id,
			'updated_by' => $a->id,
			'user_id'    => $a->id,
			'first_name' => 'John',
			'last_name'  => 'Cashier'
		));

		// Users same Patient
		$a = User::create(array(
			'group_id' => $user_group->id,
			'status'   => 'active'
		));
		$a->addRole(array($user->id));
		UserCredential::create(array(
			'user_id'  => $a->id,
			'email'    => 'user@example.com',
			'password' => Hash::make('password'),
		));
		UserMeta::create(array(
			'user_id'    => $a->id,
			'created_by' => $a->id,
			'updated_by' => $a->id,
			'first_name' => 'John',
			'last_name'  => 'Users'
		));

		foreach ($users["data"] as $u) {
			# code...
			// "first_name",
			// "last_name",
			// "email",
			// "phone",
			// "address",
			// "birthday",
			// "company",
			// "country",
			// "postal",
			// "city",
			// "gender"
			$a = User::create(array(
				'group_id' => $user_group->id,
				'status'   => 'active'
			));
			$roleid = rand(2,6);
			$a->addRole(array($roleid));
			UserCredential::create(array(
				'user_id'  => $a->id,
				'email'    => $u[2],
				'password' => Hash::make('password'),
			));
			UserMeta::create(array(
				'user_id'    => $a->id,
				'created_by' => $a->id,
				'updated_by' => $a->id,
				'first_name' => $u[0],
				'last_name'  => $u[1],
				'phone'      => $u[3],
				'address1'   => $u[4],
				'birthday'   => $u[5],
				'company'    => $u[6],
				'country'    => $u[7],
				'postal'     => $u[8],
				'province'   => $u[9],
				'gender'     => $u[10],
			));
		}

		
		$message = Message::create(array(
			'id' => 1,
			'body' => 'Hi, Admin',
			'receiver' => 1,
			'sender' => 2,
		));

		$message = Message::create(array(
			'id' => 2,
			'body' => 'Hi, Doctor',
			'receiver' => 2,
			'sender' => 1,
		));
	}

}