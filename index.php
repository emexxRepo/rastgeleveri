<?php 
/** BU UYGULAMA DATABASE İSMİ VERİLEREK DATABASENIN TÜM TABLOLARINA RANDOM VERİ EKLER !


Database Connect bilgilerini değiştirerek. ve Spechy classına database ismini yazarak bağlanabilirsiniz.
133. Satır'da database adını yazın.


BU uygulamayı dilediğiniz gibi dağıtabilir ve geliştirebilirsiniz 

* 
*/
class Spechy
{
	public $tables = array(); // DATABASEDEN GELEN TÜM TABLOLARI tables arrayına atar.
	public $fields = array(); // DATABASEDEN GELEN TÜM TABLOLARIN kolonlarını fields arrayına atar.
	public $data;  // data alan değer not tablonun kolununu alır ve random sayı türeterek birleştirir.
	public $db; // database işlemleri
	public function __construct($veritabani)
	{
		try 
		{

		 $this->db = new PDO("mysql:host=localhost;dbname=$veritabani;charset=utf8", "root", "");

		}
		catch ( PDOException $e )
		{

		     print $e->getMessage(); // DATABASE ERROR 

		}

	} // const kapanış


		 public function randomData($fieldname,$int1,$int2)
		 {
		 	$randomdata = $fieldname.rand($int1,$int2);// Random Datayı ve Tablodaki kolon ismini alır ve random sayı üreterek birleştirir
		 	return $randomdata; // Datayı Döndürür
		 }



		  public function selectColumn($tablename) // Tablodaki Kolonları Alır
		  {
		  	$query = $this->db->prepare("SHOW COLUMNS FROM $tablename");
			$query->execute();
			unset($value,$result);	
			$result =$query->fetchAll();
			
			if ( $query->rowCount()){
				
				
				
			    foreach ($result as $key => $value)
				 {
					echo "<br>";
											
					$this->fields[]= $value["Field"];
					$this->tables[$tablename][] =$value["Field"]; //  table arrayına kolonların adlarını ekler Örnek [users][email] 
					$this->data[$tablename][$value["Field"]] = $this->randomData($value["Field"],0,1000); //0 ile 1000 arasında sayılar üretir.
					
				 }

				 $this->addData($this->data[$tablename],$tablename);// data ekle fonksiyonunu çağırır ve datayı verir.



			} // count true ise

		  }
		 

		  public function addData($data=array(),$tablename)
		  {
		  		$str = "INSERT INTO $tablename (";
		  		$cols = implode(",",$this->tables[$tablename]); // İMPLODE İLE Tables Dizisindeki Tablo adını vererek
		  		$str.=$cols;                                    // tablo adının fieldlerini çeker
		  		$data = "'".implode("','",$data)."'";

		  		$datacount= count($data);
				$str.=") VALUES (";
				$str.=$data;
				$str.=")";
				echo $str;

				$insert = $this->db->query($str); // str queryisini çalıştırır.
				if ($insert) {
					echo "İnsert OK"; // INSERT İŞLEMİ SONUÇ
				}
				else
				{
					echo "İnsert FALSE";
				}


		  }
		  

		public function allTable()
		{
					    $query = $this->db->prepare("SHOW TABLES");
						$query->execute();	

						$result = $query->fetchAll();
						
						foreach ($result as $key => $value)
						 {
						 
						 	
						 	$alt_cizgi = "_";
						 	$metin = str_replace($alt_cizgi," ", $value[0]);

							$this->tables[]= $value[0]; // Value[0] TABLO ADLARIDIR !
							echo "<hr>Tablo  İsmi : ".$value[0];
							$this->selectColumn($value[0]);


						 }// tüm tabloları çekerek  tables arrayına atadı ve selectColum işlemine geçti.

						 echo "<hr>";
						 print_r($this->tables);// TÜM TABLOLARI VE KOLONLARINI EKRANA BASAR !
						  echo "<hr>
									<br><h3>Eklenen Veri</h3><br>";
						  print_r($this->data); // GÖNDERİLEN DATAYI EKRANA BASAR !
						

		}





} // class kapanış

$spechy = new Spechy('spechy');// lütfen database adını parametreye verin.


$spechy->allTable(); // all table fonskyinonu çalıştırır ve işlem otomatik gerçekleşir.







 ?>

